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
     * Returns table with the given name
     *
     * @param string $tableName
     * @return array
     */
    public function getTableContest(string $tableName)
    {
        return $this->getDatabase()[$tableName];
    }

    /**
     * Persists entity to database
     *
     * @param $entity
     * @return mixed
     * @throws UnsupportedEntity
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

    private function getDatabase()
    {
        return json_decode(file_get_contents(Parameters::DB_PATH), true);
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

        $personIds = array_map(
            function ($person) {
                return $person['id'];
            },
            $persons);

        $id = count($personIds) > 0 ? max($personIds) + 1 : 1;

        $personToPersist = $this->mapPersonToDbArray($person, $id);
        $personLanguagesToPersist = $this->mapPersonLanguagesToDbArray($person, $existingLanguages);

        $personLanguages[] = [
            'personId' => $id,
            'languages' => $personLanguagesToPersist
        ];

        $persons[] = $personToPersist;

        $updatedDb = [
            'person' => $persons,
            'language' => $existingLanguages,
            'person_language' => $personLanguages
        ];

        $this->updateDb($updatedDb);
    }

    private function persistLanguage(Language $language)
    {
        $db = $this->getDatabase();
        $languages = $db['language'];
        if ($this->languageExists($language)) {
            throw new LanguageAlreadyExists();
        }
        $languages[] = ['name' => $language->getName()];

        $db['language'] = $languages;
        $this->updateDb($db);
    }

    private function updateDb($updatedDb)
    {
        file_put_contents(Parameters::DB_PATH, json_encode($updatedDb));
    }

    private function mapPersonToDbArray(Person $person, $id)
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

    private function mapPersonLanguagesToDbArray(Person $person, &$existingLanguages)
    {
        $personLanguagesToPersist = [];

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

            $personLanguagesToPersist[] = $name;
        }

        return $personLanguagesToPersist;
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
}