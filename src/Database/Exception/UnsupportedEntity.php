<?php

namespace Demo\Database;

/**
 * Exception is thrown when DatabaseManager doesn't support given entity
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class UnsupportedEntity extends \Exception
{
    public function __construct(
        $message = "Entity you tried to manage is not supported by DatabaseManager",
        $code = 0,
        \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}