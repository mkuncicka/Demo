<?php

namespace Demo\Database;

use Demo\Config\Parameters;
use Demo\Model\Language;
use Demo\Model\Person;

/**
 * Manager class for json file based database
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class JsonDatabaseManager implements DatabaseManager
{
    /**
     * @inheritdoc
     */
    public function persist($entity)
    {
        if ($entity instanceof Person) {
            $this->persistPerson($entity);
        } elseif ($entity instanceof Language) {
            $this->persistLanguage($entity);
        } else {
            throw new UnsupportedEntity();
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($entity)
    {
        if ($entity instanceof Person) {
            $this->removePerson($entity);
        } elseif ($entity instanceof Language) {
            $this->removeLanguage($entity);
        } else {
            throw new UnsupportedEntity();
        }
    }
    /**
     * @inheritdoc
     */
    public function getAll(string $entityClassName, array $filters = [], bool $caseSensitive = true)
    {
        if ($entityClassName === Person::class) {
            $persons = $this->getTableContest('person');
            $personsLanguages = $this->getTableContest('person_language');

            $result =  $this->createPersonsFromDbType($persons, $personsLanguages);

        } elseif ($entityClassName === Language::class) {
            $languages = $this->getTableContest('language');

            $result = $this->createLanguagesFromDbType($languages);
        } else {
            throw new UnsupportedEntity();
        }

        if (!empty($filters)) {
            $result = $this->filterResult($result, $filters, $caseSensitive);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getOneById(string $entityClassName, int $id)
    {
        if ($entityClassName !== Person::class) {
            throw new UnsupportedEntity("Entity you are trying to fetch does not have id");
        }

        $result = $this->getAll(Person::class, ['id' => $id]);
        if (count($result) > 1) {
            throw new \PDOException("Multiply records identified by given ID");
        }

        return !empty($result) ? $result[0] : null;
    }

    /**
     * Removes person from database
     *
     * @param Person $person
     * @return void
     */
    public function removePerson(Person $person)
    {
        $reflectionClass = new \ReflectionClass(Person::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $id = $reflectionProperty->getValue($person);

        $db = $this->getDatabase();
        $persons = $db['person'];
        $personLanguages = $db['person_language'];

        foreach ($persons as $personKey=>$person) {
            if ($person['id'] === (int) $id) {
                array_splice($persons, $personKey, 1);

                foreach ($personLanguages as $languageKey=>$personLanguage) {
                    if ($personLanguage['personId'] === (int) $id) {
                        array_splice($personLanguages, $languageKey, 1);
                    }
                }
            }
        }

        $db['person'] = $persons;
        $db['person_language'] = $personLanguages;

        $this->updateDb($db);
    }

    /**
     * Removes language identified by given name
     *
     * @param Language $language
     * @return void
     * @throws LanguageNotFound
     * @throws LanguageUsed
     */
    private function removeLanguage(Language $language)
    {
        if ($this->languageExists($language) === false) {
            throw new LanguageNotFound();
        }

        if ($this->isLanguageKnownByAnyone($language) === true) {
            throw new LanguageUsed();
        }

        $db = $this->getDatabase();

        $existingLanguages = $db['language'];

        foreach ($existingLanguages as $key=>$existingLanguage) {
            if ($existingLanguage['name'] === $language->getName()) {
                array_splice($existingLanguages, $key, 1);
                break;
            }
        }
        $db['language'] = $existingLanguages;

        $this->updateDb($db);
    }

    /**
     * @param Person $person
     */
    private function persistPerson(Person $person)
    {
        $db = $this->getDatabase();

        $persons = $db['person'];
        $existingLanguages = $db['language'];
        $personLanguages = $db['person_language'];

        $id = $db['last_inserted_person_id'] + 1;

        $personToPersist = $this->createPersonDbRecord($person, $id);
        $personLanguagesToPersist = $this->createPersonLanguagesRecord($person);
        $existingLanguages = $this->updateLanguagesDbRecordsWithPersonLanguages($person, $existingLanguages);

        $personLanguages[] = [
            'personId' => $id,
            'languages' => $personLanguagesToPersist
        ];

        $persons[] = $personToPersist;

        $updatedDb = [
            'person' => $persons,
            'language' => $existingLanguages,
            'person_language' => $personLanguages,
            'last_inserted_person_id' => $id
        ];

        $this->updateDb($updatedDb);
    }

    private function persistLanguage(Language $language)
    {
        if ($this->languageExists($language)) {
            throw new LanguageAlreadyExists();
        }

        $db = $this->getDatabase();
        $languages = $db['language'];
        $languages[] = ['name' => $language->getName()];

        $db['language'] = $languages;
        $this->updateDb($db);
    }

    /**
     * Creates person record from new Person entity with given id
     * @param Person $person
     * @param $id
     * @return array
     */
    private function createPersonDbRecord(Person $person, $id)
    {
        $reflectionClass = new \ReflectionClass(Person::class);
        $reflectionFirstName = $reflectionClass->getProperty('firstName');
        $reflectionLastName = $reflectionClass->getProperty('lastName');
        $reflectionFirstName->setAccessible(true);
        $reflectionLastName->setAccessible(true);

        return [
            'id' => $id,
            'firstName' => $reflectionFirstName->getValue($person),
            'lastName' => $reflectionLastName->getValue($person)
        ];
    }

    /**
     * Creates person_language record from new Person entity
     *
     * @param Person $person
     * @return array
     */
    private function createPersonLanguagesRecord(Person $person)
    {
        $personLanguagesToPersist = [];

        foreach ($person->getLanguages() as $language) {
            $personLanguagesToPersist[] = $language->getName();
        }

        return $personLanguagesToPersist;
    }

    /**
     * Updates language records with new languages persisted with person
     *
     * @param Person $person
     * @param array $existingLanguages
     * @return array
     */
    private function updateLanguagesDbRecordsWithPersonLanguages(Person $person, array $existingLanguages)
    {
        $languageNames = array_map(
            function ($language) {
                return $language['name'];
            },
            $existingLanguages);

        foreach ($person->getLanguages() as $language) {
            $name = $language->getName();
            if (array_search($name, $languageNames) === false) {
                $existingLanguages[] = ['name' => $name];
            }

        }

        return $existingLanguages;

    }

    /**
     * Checks if language already exists
     *
     * @param Language $language
     * @return bool
     */
    private function languageExists(Language $language)
    {
        $existingLanguages = $this->getDatabase()['language'];
        foreach ($existingLanguages as $existingLanguage) {
            if (strtolower($language->getName()) === strtolower($existingLanguage['name'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if anyone know given language
     *
     * @param Language $language
     * @return bool
     */
    private function isLanguageKnownByAnyone(Language $language)
    {
        $personLanguages = $this->getDatabase()['person_language'];
        foreach ($personLanguages as $personLanguage) {
            $mappedLanguages = array_map(function($language){return strtolower($language);},$personLanguage['languages']);
            if (in_array(strtolower($language->getName()), $mappedLanguages)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $persons
     * @param array $personsLanguages
     * @return array
     */
    private function createPersonsFromDbType(array $persons, array $personsLanguages)
    {
        $result = [];
        $personLanguages = [];

        foreach ($personsLanguages as $personLanguage) {
            $personLanguages[$personLanguage['personId']] = $personLanguage['languages'];
        }

        foreach ($persons as $person) {
            $id = $person['id'];

            $personEntity = new Person($person['firstName'], $person['lastName'], $personLanguages[$id]);
            $reflectionClass = new \ReflectionClass(Person::class);
            $reflectionProperty = $reflectionClass->getProperty('id');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($personEntity, $id);

            $result[] = $personEntity;
        }

        return $result;
    }

    /**
     * @param array $languages
     * @return array
     */
    private function createLanguagesFromDbType(array $languages)
    {
        $result = [];

        foreach ($languages as $language) {
            $result[] = new Language($language['name']);
        }

        return $result;
    }

    private function filterResult(array $items, array $filters, bool $caseSensitive = true)
    {
        $result = [];

        foreach ($filters as $fieldName => $value) {
            foreach ($items as $key=> $item) {
                $reflectionClass = new \ReflectionClass($item);
                $reflectionProperty = $reflectionClass->getProperty($fieldName);
                $reflectionProperty->setAccessible(true);

                $propertyValue = $reflectionProperty->getValue($item);

                if (is_string($value) && ($caseSensitive === false)) {
                    $condition = (strtolower($propertyValue) === strtolower($value));

                } else {
                    $condition = ($propertyValue === $value);
                }

                if ($condition) {
                    $result[] = $item;
                }
            }
        }

        return $result;
    }

    /**
     * Returns whole database as array
     *
     * @return array
     */
    private function getDatabase()
    {
        return json_decode(file_get_contents(Parameters::DB_PATH), true);
    }

    /**
     * Updates whole database
     *
     * @param $updatedDb
     */
    private function updateDb($updatedDb)
    {
        file_put_contents(Parameters::DB_PATH, json_encode($updatedDb));
    }

    /**
     * Returns table with the given name
     *
     * @param string $tableName
     * @return array
     */
    private function getTableContest(string $tableName)
    {
        return $this->getDatabase()[$tableName];
    }
}