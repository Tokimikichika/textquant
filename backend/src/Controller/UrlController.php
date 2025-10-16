<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Controller\Base\AbstractController;
use Tokimikichika\Find\Service\UrlAnalysisService;

/**
 * Контроллер для анализа текста по URL.
 *
 * Отвечает за приём входных данных и делегирование
 * в доменные сервисы. Исключения сервисов мапятся
 * на HTTP-коды в JSON-ответах.
 */
class UrlController extends AbstractController
{
    /**
     * @param UrlAnalysisService $urlAnalysisService Сервис анализа содержимого страницы по URL
     */
    public function __construct(
        private UrlAnalysisService $urlAnalysisService
    ) {
    }

    /**
     * Анализирует содержимое веб-страницы по URL.
     *
     * @param Request  $request  HTTP-запрос (ожидается JSON с ключом `url`)
     * @param Response $response HTTP-ответ
     *
     * @return Response JSON-ответ с результатами анализа или сообщением об ошибке
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
