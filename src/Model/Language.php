<?php

namespace Demo\Model;

/**
 * Language model
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class Language
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}