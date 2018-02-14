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
     * Returns array of Person object
     *
     * @return Person[]
     */
    public function getAll();
}