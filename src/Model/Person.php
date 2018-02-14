<?php

namespace Demo\Model;

/**
 * Person model
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class Person
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var Language[]
     */
    private $languages;

    /**
     * Person constructor.
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param Language[] $languages
     */
    public function __construct(int $id, string $firstName, string $lastName, array $languages)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->languages = $languages;
    }

    public function __toString()
    {
        $languages = implode(', ', $this->languages);
        $result = "$this->id. $this->firstName $this->lastName - ($languages)\n";

        return $result;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

}