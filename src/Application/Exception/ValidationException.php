<?php

namespace Demo\Application\Exception;

/**
 * Exception is thrown when validation fails
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class ValidationException extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        $message = "Validation failed. " . $message;

        parent::__construct($message, $code, $previous);
    }
}