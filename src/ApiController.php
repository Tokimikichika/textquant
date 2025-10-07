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
     * Анализирует текст
     */
    public function analyzeText(Request $request, Response $response): Response
    {
        $body = (array) ($request->getParsedBody() ?? []);
        $text = trim((string) ($body['text'] ?? ''));

        if ($text === '') {
            return $this->json($response, ['error' => 'Text is required'], 400);
        }

        try {
            $results = $this->analyzer->analyze($text, 'text');
            return $this->json($response, $results, 200);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => 'Internal error', 'details' => $e->getMessage()], 500);
        }
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


