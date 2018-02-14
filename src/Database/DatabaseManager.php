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
     * Returns table with the given name
     *
     * @param string $tableName
     * @return array
     */
    public function getTableContest(string $tableName);

}