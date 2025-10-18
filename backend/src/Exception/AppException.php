<?php

namespace Tokimikichika\Find\Exception;

/**
 * Базовое исключение домена приложения.
 */
class AppException extends \RuntimeException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}


