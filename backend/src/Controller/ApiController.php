<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\RandomTextService;

class ApiController
{
    public function __construct(
        private TextAnalyzer $analyzer,
        private RandomTextService $randomTextService
    ) {
    }

    /**
     * Анализирует текст
     */
    public function analyzeText(Request $request, Response $response): Response
    {
        $results = $this->analyzer->analyze($request->getBody()->getContents(), 'text');
        $payload = json_encode($results, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * Возвращает сгенерированный текст из внешнего сервиса
     */
    public function randomText(Request $request, Response $response): Response
    {
        $text = $this->randomTextService->get();

        $payload = json_encode(['text' => $text], JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
    }

}



