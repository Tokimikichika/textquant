<?php

namespace Tokimikichika\Find;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Контроллер для API
 */
class ApiController
{
    private TextAnalyzer $analyzer;

    public function __construct(TextAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }



    /**
     * Форматирует данные в JSON
     */
    private function json(Response $response, array $data, int $status): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status);
    }
}


