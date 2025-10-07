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
    public function analyze(Request $request, Response $response): Response
    {
        [$text, $source, $error] = $this->getRequestData($request);

        $results = null;
        if ($error === '' && $text !== '') {
            $results = $this->analyzeSafely($text, $source, $error);
        }

        $data = [
            'text' => $text,
            'source' => $source,
            'error' => $error,
            'results' => $results,
        ];

        return $this->renderHtml($response, array_merge($data, ['formatter' => $this->formatter]));
    }

    /**
     * Извлекает и валидирует входные данные из запроса
     * @return array{0:string,1:string,2:string} [text, source, error]
     */
    private function getRequestData(Request $request): array
    {
        $body = (array) ($request->getParsedBody() ?? []);
        $uploadedFiles = $request->getUploadedFiles();

        $text = '';
        $source = 'text';
        $error = '';

        $textInput = trim((string) ($body['text'] ?? ''));
        $file = $uploadedFiles['file'] ?? null;

        $hasText = ($textInput !== '');
        $hasFile = ($file && $file->getError() === UPLOAD_ERR_OK);

        if ($hasText && $hasFile) {
            $error = 'Введите текст ИЛИ выберите файл, но не оба сразу';
            return [$text, $source, $error];
        }

        if ($hasText) {
            $text = $textInput;
            $source = 'text';
            return [$text, $source, $error];
        }

        if ($hasFile) {
            try {
                $tmpPath = $file->getStream()->getMetadata('uri');
                $text = $this->textReader->readFromFile($tmpPath);
                $source = $file->getClientFilename() ?? 'file';
            } catch (\Throwable $e) {
                $error = 'Ошибка чтения файла: ' . $e->getMessage();
            }
            return [$text, $source, $error];
        }

        $error = 'Пожалуйста, введите текст или выберите файл';
        return [$text, $source, $error];
    }

    /**
     * Безопасный вызов анализатора с обработкой ошибок
     */
    private function analyzeSafely(string $text, string $source, string &$error): ?array
    {
        try {
            return $this->analyzer->analyze($text, $source);
        } catch (\Throwable $e) {
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

