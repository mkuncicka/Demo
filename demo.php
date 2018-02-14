#!/usr/bin/env php
<?php
require_once 'classes.php';

$function = "_" . $argv[1];
$arguments = array_slice($argv, 2);

echo $function($arguments);

function _list() {
    $dbManager = new Demo\Database\JsonDatabaseManager();
    $personsRepository = new \Demo\Repository\PersonsRepository($dbManager);
    foreach ($personsRepository->getAll() as $person) {
        print $person;
    }

    return;
}

function _find($args) {
    if (count($args) == 0) {
        print "Part of the full name not passed\n";exit;
    }

    $name = implode( ' ', $args);
    $dbManager = new Demo\Database\JsonDatabaseManager();
    $personsRepository = new \Demo\Repository\PersonsRepository($dbManager);
    foreach ($personsRepository->getByName($name) as $person) {
        print $person;
    }
}

?>