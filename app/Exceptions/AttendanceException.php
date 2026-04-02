<?php

namespace App\Exceptions;

use RuntimeException;

class AttendanceException extends RuntimeException
{
    public function __construct(string $message, private int $httpStatus = 422)
    {
        parent::__construct($message);
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}