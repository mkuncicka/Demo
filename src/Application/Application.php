<?php

namespace Demo\Application;

use Demo\Database\DatabaseManager;
use Demo\Database\JsonDatabaseManager;
use Demo\Repository\Languages;
use Demo\Repository\LanguagesRepository;
use Demo\Repository\Persons;
use Demo\Repository\PersonsRepository;

/**
 * Application main class
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class Application
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @var Languages
     */
    private $languages;

    /**
     * @var Persons
     */
    private $persons;

    /**
     * @var Validator
     */
    private $validator;

    public function __construct()
    {
        $this->databaseManager = new JsonDatabaseManager();
        $this->languages = new LanguagesRepository($this->databaseManager);
        $this->persons = new PersonsRepository($this->databaseManager);
        $this->validator = new Validator();
        $this->commandHandler = new CommandHandler($this->databaseManager, $this->persons, $this->languages, $this->validator);
    }

    /**
     * Handles command typed by user
     *
     * @param array $argv
     */
    public function handleCommand(array $argv)
    {
        if (count($argv) <= 1) {
            return;
        }
        $function = $argv[1];
        $arguments = array_slice($argv, 2);

        try {
            $this->commandHandler->$function($arguments);
        } catch (\Exception $e) {
            print "Call of the function: $function failed. \nMessage: " . $e->getMessage() . "\n";

            foreach ($e->getTrace() as $tracePart) {
                print json_encode($tracePart) . "\n";
            }
        }
    }
}