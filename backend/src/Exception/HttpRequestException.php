<?php

namespace Tokimikichika\Find\Exception;

/**
 * Исключение для ошибок HTTP-запросов
 */
class HttpRequestException extends AppException
{
    public function __construct(string $url = '', string $error = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = 'Ошибка HTTP-запроса';
        if ($url) {
            $message .= " к {$url}";
        }
        if ($error) {
            $message .= ": {$error}";
        }
        parent::__construct($message, $code, $previous);
    }
}
