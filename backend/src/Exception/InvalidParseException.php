<?php

namespace Tokimikichika\Find\Exception;

/**
 * Исключение для ошибок парсинга
 */
class InvalidParseException extends AppException
{
    public function __construct(string $data = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = $data ? "Ошибка парсинга данных: {$data}" : 'Ошибка парсинга данных';
        parent::__construct($message, $code, $previous);
    }
}
