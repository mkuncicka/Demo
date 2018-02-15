<?php

namespace Demo\Database;

use Demo\Model\Language;

/**
 * Describes database manager implementation
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
interface DatabaseManager
{
    /**
     * Returns table with the given name
     *
     * @param string $tableName
     * @return array
     */
    public function getTableContest(string $tableName);

    /**
     * Persists entity to database
     *
     * @param $entity
     * @return void
     */
    public function persist($entity);

    /**
     * Removes person identified by given id
     *
     * @param $id
     * @return void
     */
    public function removePerson($id);

    /**
     * Removes language identified by given name
     *
     * @param Language $language
     * @return void
     */
    public function removeLanguage(Language $language);

}