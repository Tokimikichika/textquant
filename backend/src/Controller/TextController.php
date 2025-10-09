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
     */
    public function analyze(Request $request, Response $response): Response
    {
        $results = $this->analyzer->analyze($request->getBody()->getContents(), 'text');
        $payload = json_encode($results, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
