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

function _languages($args) {
    $dbManager = new Demo\Database\JsonDatabaseManager();
    $personsRepository = new \Demo\Repository\PersonsRepository($dbManager);

    foreach ($personsRepository->getByLanguages($args) as $person) {
        print $person;
    }
}

function _addPerson($args) {

    $dbManager = new Demo\Database\JsonDatabaseManager();
    $personsRepository = new \Demo\Repository\PersonsRepository($dbManager);
    $languages = [];
    try {
        foreach (array_splice($args, 2) as $languageName) {
            $languages[] = new \Demo\Model\Language($languageName);
        }
        $person = new \Demo\Model\Person($args[0], $args[1], $languages);
        $personsRepository->add($person);
        print "Person addition succeed\n";
    } catch (\Exception $e) {
        print "Person addition faild. Message: " . $e->getMessage() . "\n";
        print "Trace: \n" . json_encode($e->getTrace()) . "\n";
    }
}

function _addLanguage($args) {
    try {
        $dbManager = new Demo\Database\JsonDatabaseManager();
        $languagesRepository = new \Demo\Repository\LanguagesRepository($dbManager);
        $language = new \Demo\Model\Language($args[0]);
        $languagesRepository->add($language);
        print "Language addition succeed\n";
    } catch (\Exception $e) {
        print "Language addition faild. Message: " . $e->getMessage() . "\n";
        print "Trace: \n" . json_encode($e->getTrace()) . "\n";
    }
}

function _removePerson($args) {
    $dbManager = new Demo\Database\JsonDatabaseManager();
    $personsRepository = new \Demo\Repository\PersonsRepository($dbManager);

    try {
        $personsRepository->removeById($args[0]);
        print "Person deletion succeed\n";
    } catch (\Exception $e) {
        print "Person deletion faild. Message: " . $e->getMessage() . "\n";
        print "Trace: \n" . json_encode($e->getTrace()) . "\n";
    }
}

function _removeLanguage($args) {
    try {
        $dbManager = new Demo\Database\JsonDatabaseManager();
        $languagesRepository = new \Demo\Repository\LanguagesRepository($dbManager);
        $language = new \Demo\Model\Language($args[0]);
        $languagesRepository->remove($language);
        print "Language deletion succeed\n";
    } catch (\Exception $e) {
        print "Language deletion faild. Message: " . $e->getMessage() . "\n";
        print "Trace: \n" . json_encode($e->getTrace()) . "\n";
    }
}

?>