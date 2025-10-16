<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Service\TextAnalyzer;

class TextController
{
    public function __construct(
        private TextAnalyzer $analyzer
    ) {
    }

    /**
     * Анализирует текст
     * @param Request $request HTTP-запрос
     * @param Response $response HTTP-ответ
     * @return Response JSON-ответ с результатами анализа или сообщением об ошибке
     */
    public function analyze(Request $request, Response $response): Response
    {
        $raw = $request->getBody()->getContents();
        $data = json_decode($raw, true);

        if (!is_array($data) || !isset($data['text']) || !is_string($data['text'])) {
            $response->getBody()->write(json_encode(['error' => 'Field "text" is required'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }

        $results = $this->analyzer->analyze($data['text'], 'text');
        $payload = json_encode($results, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
