<?php

namespace Tokimikichika\Find\Exception;

/**
 * Исключение для ошибок неверного URL
 */
class InvalidUrlException extends AppException
{
    public function __construct(string $url = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = $url ? "Неверный URL: {$url}" : 'Неверный URL';
        parent::__construct($message, $code, $previous);
    }
}
