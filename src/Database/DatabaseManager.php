<?php

namespace Demo\Database;

/**
 * Describes database manager implementation
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
interface DatabaseManager
{
    /**
     * Persists entity to database
     *
     * @param $entity
     * @return void
     */
    public function persist($entity);

    /**
     * Removes entity from database
     *
     * @param $entity
     * @return void
     */
    public function remove($entity);

    /**
     * Returns all data of given type filtered by given fields
     *
     * @param string $entityClassName
     * @param array $filters
     * @param bool $caseSensitive
     * @return array
     */
    public function getAll(string $entityClassName, array $filters = [], bool $caseSensitive = true);

    /**
     * Returns single entity identified by id
     *
     * @param string $entityClassName
     * @param int $id
     */
    public function getOneById(string $entityClassName, int $id);

}