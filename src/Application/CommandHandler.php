<?php

namespace Demo\Application;

use Demo\Database\DatabaseManager;
use Demo\Model\Language;
use Demo\Model\Person;
use Demo\Repository\Languages;
use Demo\Repository\Persons;

/**
 * Handles commands
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class CommandHandler
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;
    /**
     * @var Persons
     */
    private $persons;
    /**
     * @var Languages
     */
    private $languages;

    public function __construct(DatabaseManager $databaseManager, Persons $persons, Languages $languages)
    {
        $this->databaseManager = $databaseManager;
        $this->persons = $persons;
        $this->languages = $languages;
    }

    /**
     * Lists all Persons
     *
     * @param $args
     */
    public function list($args)
    {
        foreach ($this->persons->getAll() as $person) {
            print $person;
        }
    }

    /**
     * Prints Persons whose full name matches given part
     *
     * @param $args
     */
    public function find($args)
    {
        if (count($args) == 0) {
            print "Part of the full name not passed\n";
            exit;
        }

        $name = implode( ' ', $args);

        foreach ($this->persons->getByName($name) as $person) {
            print $person;
        }
    }

    /**
     * Lists all Persons who know all of the given Languages
     *
     * @param $args
     */
    public function languages($args)
    {
        foreach ($this->persons->getByLanguages($args) as $person) {
            print $person;
        }
    }

    /**
     * Initialize addition of the Person to database
     *
     * @param $args
     */
    public function addPerson($args)
    {
        $languages = [];
        foreach (array_splice($args, 2) as $languageName) {
            $languages[] = new Language($languageName);
        }

        $person = new Person($args[0], $args[1], $languages);
        $this->persons->add($person);

        print "Person addition succeed\n";
    }

    /**
     * Initialize addition of the Language to database
     *
     * @param $args
     */
    public function addLanguage($args)
    {
        $language = new Language($args[0]);
        $this->languages->add($language);

        print "Language addition succeed\n";
    }

    /**
     * Initialize removal of the Person from database
     *
     * @param $args
     */
    public function removePerson($args)
    {
        $this->persons->removeById($args[0]);

        print "Person deletion succeed\n";
    }

    /**
     * Initialize removal of the Language from database
     *
     * @param $args
     */
    public function removeLanguage($args)
    {
        $language = new Language($args[0]);
        $this->languages->remove($language);

        print "Language deletion succeed\n";
    }
}