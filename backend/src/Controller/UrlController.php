<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Service\UrlAnalysisService;

class UrlController
{
    public function __construct(
        private UrlAnalysisService $urlAnalysisService
    ) {
    }

    /**
     * Анализирует содержимое веб-страницы по URL
     */
    public function analyze(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        if ($data === null) {
            $data = json_decode($request->getBody()->getContents(), true) ?: [];
        }
        $url = $data['url'] ?? null;

        if (empty($url)) {
            return $this->json($response, ['error' => 'URL is required'], 400);
        }

        try {
            $result = $this->urlAnalysisService->analyzeUrl($url);
            return $this->json($response, $result);
        } catch (\Throwable $e) {
            return $this->json($response, ['error' => $e->getMessage()], 500);
        }
    }

}
