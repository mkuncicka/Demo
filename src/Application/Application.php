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

    public function __construct()
    {
        $this->databaseManager = new JsonDatabaseManager();
        $this->languages = new LanguagesRepository($this->databaseManager);
        $this->persons = new PersonsRepository($this->databaseManager);
        $this->commandHandler = new CommandHandler($this->databaseManager, $this->persons, $this->languages);
    }

    public function handleCommand($argv)
    {
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