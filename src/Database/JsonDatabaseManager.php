<?php

namespace Demo\Database;

use Demo\Config\Parameters;

/**
 * Manager class for json file based database
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class JsonDatabaseManager implements DatabaseManager
{
    public function getTableContest(string $tableName)
    {
        return json_decode(file_get_contents(Parameters::DB_PATH), true)[$tableName];
    }
}