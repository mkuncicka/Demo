<?php

namespace Demo\Database;

/**
 * Exception is thrown when DatabaseManager doesn't support given entity
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class LanguageAlreadyExists extends \Exception
{
    public function __construct(
        $message = "Language you tried to add already exist",
        $code = 0,
        \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}