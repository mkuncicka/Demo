<?php

namespace Demo\Application;

use Demo\Application\Exception\ValidationException;

/**
 * Delivers validation methods
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class Validator
{
    /**
     * Validates if argument is not null
     *
     * @param $argument
     * @return bool
     * @throws ValidationException
     */
    public function isNotNull($argument)
    {
        if ($argument === null) {
            throw new ValidationException("Value can not be null.");
        }

        return true;
    }

    /**
     * Validates if argument is not an empty string
     *
     * @param string $argument
     * @return bool
     * @throws ValidationException
     */
    public function isNotEmptyString(string $argument)
    {
        if ($argument === '') {
            throw new ValidationException("Value can not be empty string");
        }

        return true;
    }

    /**
     * Validates if argument contains only alphabetical characters
     *
     * @param $argument
     * @return bool
     * @throws ValidationException
     */
    public function isAlphabetical($argument)
    {
        if (is_string($argument) === false) {
            throw new ValidationException("Value must be type of string.");
        }

        if (preg_match("/^[A-z]+$/", $argument) === 0) {
            throw new ValidationException("Value must by alphabetical - only letters allowed.");
        }

        return true;
    }

    /**
     * Validates if the array is not empty
     *
     * @param array $argument
     * @return bool
     * @throws ValidationException
     */
    public function notAnEmptyArray(array $argument)
    {
        if (count($argument) === 0) {
            throw new ValidationException("Array cannot be empty.");
        }

        return true;
    }

    /**
     * Validates if the array has at least expected number of elements
     *
     * @param array $argument
     * @param int $minCount
     * @throws ValidationException
     */
    public function hasMinimumNumberOfElements(array $argument, int $minCount)
    {
        if (count($argument) < $minCount) {
            throw new ValidationException("Too few arguments - at least $minCount expected.");
        }
    }
}