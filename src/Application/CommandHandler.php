<?php

namespace Demo\Application;

use Demo\Application\Exception\NotFoundException;
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
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(DatabaseManager $databaseManager, Persons $persons, Languages $languages, Validator $validator)
    {
        $this->databaseManager = $databaseManager;
        $this->persons = $persons;
        $this->languages = $languages;
        $this->validator = $validator;
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
        $this->validator->notAnEmptyArray($args);

        foreach ($args as $namePart) {
            $this->validator->isAlphabetical($namePart);
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
        $this->validator->notAnEmptyArray($args);

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
        $this->validator->hasMinimumNumberOfElements($args, 2);

        $firstName = $args[0];
        $lastName = $args[1];
        $languageNames = array_splice($args, 2);

        $this->validator->isAlphabetical($firstName);
        $this->validator->isAlphabetical($lastName);

        $languages = [];

        foreach ($languageNames as $languageName) {
            $languages[] = new Language($languageName);
        }

        $person = new Person($firstName, $lastName, $languages);
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
        $this->validator->notAnEmptyArray($args);

        $languageName = $args[0];
        $this->validator->isNotEmptyString($languageName);

        $language = new Language($languageName);
        $this->languages->add($language);

        print "Language addition succeed\n";
    }

    /**
     * Initialize removal of the Person from database
     *
     * @param $args
     * @throws NotFoundException
     */
    public function removePerson($args)
    {
        $this->validator->notAnEmptyArray($args);

        $person = $this->persons->getById($args[0]);

        if ($person === null) {
            throw new NotFoundException();
        }
        $this->persons->remove($person);

        print "Person deletion succeed\n";
    }

    /**
     * Initialize removal of the Language from database
     *
     * @param $args
     * @throws NotFoundException
     */
    public function removeLanguage($args)
    {
        $language = $this->languages->getByName($args[0]);

        if ($language === null) {
            throw new NotFoundException();
        }

        $this->languages->remove($language);

        print "Language deletion succeed\n";
    }

    public function __call($name, $arguments)
    {
        print "Method $name not implemented.\n";
    }
}