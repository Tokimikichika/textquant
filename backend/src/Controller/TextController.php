<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Controller\Base\AbstractController;
use Tokimikichika\Find\Service\TextAnalyzer;

class TextController extends AbstractController
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
            return $this->json($response, ['error' => 'Field "text" is required'], 400);
        }

        $results = $this->analyzer->analyze($data['text'], 'text');
        return $this->json($response, $results);
    }
}
