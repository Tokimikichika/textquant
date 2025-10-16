<?php

namespace Tokimikichika\Find\Controller\Base;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Базовый контроллер с общими вспомогательными методами.
 */
abstract class AbstractController
{
    /**
     * Форматирует и записывает JSON-ответ в поток.
     *
     * @param array<string,mixed> $data
     */
    protected function json(Response $response, array $data, int $status = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($payload === false) {
            $payload = '{"error":"Encoding error"}';
        }
        $response->getBody()->write($payload);
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
    }
}


