<?php

namespace Demo\Repository;

use Demo\Database\DatabaseManager;
use Demo\Model\Person;

/**
 * Persons repository
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class PersonsRepository implements Persons
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * PersonsRepository constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Returns array of Person object
     *
     * @return Person[]
     */
    public function getAll()
    {
        return $this->databaseManager->getAll(Person::class);
    }

    /**
     * Returns array of Person objects matching given name
     * @param $name
     * @return Person[]
     */
    public function getByName($name)
    {
        $result = [];
        $allPersons = $this->getAll();
        foreach ($allPersons as $person) {
            if (strpos($person->getFullName(), $name) !== false) {
                $result[] = $person;
            }
        }

        return $result;
    }

    /**
     * Returns array of Person objects witch knows given languages
     *
     * @param array $languagesNames
     * @return Person[]
     */
    public function getByLanguages(array $languagesNames)
    {
        $result = [];
        $allPersons = $this->getAll();

        foreach ($allPersons as $person) {
            $personLanguages = array_map(
                function ($language) {
                    return strtolower($language);
                },
                $person->getLanguages()
            );

            $hasAllLanguages = true;

            foreach ($languagesNames as $language) {
                if (!in_array(strtolower($language), $personLanguages)) {
                    $hasAllLanguages = false;
                    break;
                }
            }

            if ($hasAllLanguages) {
                $result[] = $person;
            }
        }

        return $result;
    }

    /**
     * Adds Person to database
     *
     * @param Person $person
     * @return void
     */
    public function add(Person $person)
    {
        $this->databaseManager->persist($person);
    }

    /**
     * @inheritdoc
     */
    public function remove(Person $person)
    {
        $this->databaseManager->remove($person);
    }

    /**
     * Returns Person identified by given ID
     *
     * @param int $id
     * @return Person
     */
    public function getById(int $id)
    {
        return $this->databaseManager->getOneById(Person::class, $id);
    }
}