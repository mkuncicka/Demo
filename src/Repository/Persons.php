<?php

namespace Demo\Repository;

use Demo\Model\Person;

/**
 * Describes Persons repository
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
interface Persons
{
    /**
     * Returns array of Person objects
     *
     * @return Person[]
     */
    public function getAll();

    /**
     * Returns array of Person objects matching given name
     *
     * @param $name
     * @return Person[]
     */
    public function getByName($name);

    /**
     * Returns array of Person objects witch knows given languages
     *
     * @param array $languagesNames
     * @return Person[]
     */
    public function getByLanguages(array $languagesNames);

    /**
     * Returns Person identified by given ID
     *
     * @param int $id
     * @return Person
     */
    public function getById(int $id);

    /**
     * Adds Person to database
     *
     * @param Person $person
     * @return void
     */
    public function add(Person $person);

    /**
     * Removes person from repository
     *
     * @param Person $person
     * @return void
     */
    public function remove(Person $person);
}