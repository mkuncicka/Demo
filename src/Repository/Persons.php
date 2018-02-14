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
}