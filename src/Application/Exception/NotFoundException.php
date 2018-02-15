<?php


namespace Demo\Application\Exception;

/**
 * Exception is thrown when object was not found
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class NotFoundException extends \Exception
{
    public function __construct($message = "Object not found", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}