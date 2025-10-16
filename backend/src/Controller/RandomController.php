<?php

namespace Tokimikichika\Find\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tokimikichika\Find\Controller\Base\AbstractController;
use Tokimikichika\Find\Service\RandomTextService;

class RandomController extends AbstractController
{
    public function __construct(
        private RandomTextService $randomTextService
    ) {
    }

    /**
     * Возвращает сгенерированный текст из внешнего сервиса
     * @param Request $request HTTP-запрос
     * @param Response $response HTTP-ответ
     * @return Response JSON-ответ с результатами анализа или сообщением об ошибке
     */
    public function getText(Request $request, Response $response): Response
    {
        $text = $this->randomTextService->getRandomText();

        return $this->json($response, ['text' => $text]);
    }
}
