<?php

namespace Demo\Database;

/**
 * Exception is thrown when language can not be find
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class LanguageNotFound extends \Exception
{
    public function __construct(
        $message = "Language you tried to remove doesn't exists",
        $code = 0,
        \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}