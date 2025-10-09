<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\RandomTextService;
use Tokimikichika\Find\Service\WebScraperService;

class ApiController
{
    public function __construct(
        private TextAnalyzer $analyzer,
        private RandomTextService $randomTextService,
        private WebScraperService $webScraperService
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

    /**
     * Анализирует содержимое веб-страницы по URL
     */
    public function analyzeUrl(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);
        
        if (!isset($data['url']) || empty($data['url'])) {
            $response->getBody()->write(json_encode(['error' => 'URL is required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $text = $this->webScraperService->scrapeUrl($data['url']);
            $results = $this->analyzer->analyze($text, 'url');
            $payload = json_encode($results, JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($payload);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        return $response->withHeader('Content-Type', 'application/json; charset=utf-8');
    }

}



