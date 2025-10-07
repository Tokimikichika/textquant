<?php

namespace Tokimikichika\Find;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Контроллер для веб-интерфейса
 * Обрабатывает HTTP запросы и подготавливает данные для представления
 */
class WebController
{
    private TextAnalyzer $analyzer;
    private TextReader $textReader;
    private ResultFormatter $formatter;
    private ViewRenderer $viewRenderer;

    public function __construct(
        TextAnalyzer $analyzer,
        TextReader $textReader,
        ResultFormatter $formatter,
        ViewRenderer $viewRenderer
    ) {
        $this->analyzer = $analyzer;
        $this->textReader = $textReader;
        $this->formatter = $formatter;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Отображает стартовую страницу (GET)
     */
    public function show(Request $request, Response $response): Response
    {
        $data = [
            'text' => '',
            'source' => 'text',
            'error' => '',
            'results' => null,
        ];

        $html = $this->viewRenderer->renderWithLayout('home', array_merge($data, ['formatter' => $this->formatter]));
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Обрабатывает анализ текста (POST)
     */
    public function renderResponse(array $data): string
    {
        return $this->viewRenderer->renderWithLayout('home.php', $data);
    }

    /**
     * Обрабатывает POST запрос
     */
    private function handlePostRequest(string &$text, string &$source, string &$error): void
    {
        if (isset($_POST['text']) && !empty(trim($_POST['text']))) {
            $text = trim($_POST['text']);
            $source = 'text';
        } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $this->handleFileUpload($text, $source, $error);
        } else {
            $error = 'Пожалуйста, введите текст или выберите файл';
        }
    }

    /**
     * Обрабатывает загрузку файла
     */
    private function handleFileUpload(string &$text, string &$source, string &$error): void
    {
        try {
            $text = $this->textReader->readFromFile($_FILES['file']['tmp_name']);
            $source = $_FILES['file']['name'];
        } catch (Exception $e) {
            $error = 'Ошибка чтения файла: ' . $e->getMessage();
        }
    }

    /**
     * Обрабатывает GET запрос
     */
    private function handleGetRequest(string &$text, string &$source): void
    {
        $text = "Привет мир! Это тестовый текст для демонстрации работы библиотеки анализа текста. Он содержит несколько предложений и слов для демонстрации работы библиотеки.";
        $source = 'example';
    }

    /**
     * Анализирует текст
     */
    private function analyzeText(string $text, string $source, string &$error): ?array
    {
        try {
            return $this->analyzer->analyze($text, $source);
        } catch (Exception $e) {
            $error = 'Ошибка анализа: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Рендер HTML в Response
     */
    private function renderHtml(Response $response, array $data): Response
    {
        $html = $this->viewRenderer->renderWithLayout('home', $data);
        $response->getBody()->write($html);
        return $response;
    }
}

