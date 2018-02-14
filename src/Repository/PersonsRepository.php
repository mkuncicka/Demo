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
        $persons = $this->databaseManager->getTableContest('person');
        $languages = $this->databaseManager->getTableContest('person_language');
        return $this->serialize($persons, $languages);
    }

    private function serialize(array $persons, $languages)
    {
        $result = [];
        $personLanguages = [];

        foreach ($languages as $language) {
            $personLanguages[$language['personId']] = $language['languages'];
        }

        foreach ($persons as $item) {
            $id = $item['id'];

            $result[] = new Person($id, $item['firstName'], $item['lastName'], $personLanguages[$id]);
        }

        return $result;
    }
}