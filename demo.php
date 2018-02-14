#!/usr/bin/env php
<?php
require_once 'classes.php';

$function = "_" . $argv[1];
$arguments = array_slice($argv, 2);

echo $function($arguments);

function _list() {
    $db = new Demo\Database\JsonDatabaseManager();
    $personsRepo = new \Demo\Repository\PersonsRepository($db);
    foreach ($personsRepo->getAll() as $person) {
        print $person;
    }

    return;
}

?>