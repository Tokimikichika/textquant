<?php

namespace Tokimikichika\Find\Service;

use Tokimikichika\Find\Enum\ApiConfig;

class RandomTextService
{
    /**
     * Возвращает случайный текст из внешнего источника
     */
    public function getRandomText(): string
    {
        $data = $this->fetchJson(ApiConfig::FISH_TEXT_API_URL->value);
        $this->validateApiResponse($data);
        
        return $data['text'];
    }

    /**
     * Валидирует ответ от API
     */
    private function validateApiResponse(array $data): void
    {
        $this->validateTextFieldExists($data);
        $this->validateTextFieldType($data);
        $this->validateTextFieldNotEmpty($data);
    }

    /**
     * Проверяет наличие поля text в ответе
     */
    private function validateTextFieldExists(array $data): void
    {
        if (!isset($data['text'])) {
            throw new \RuntimeException('Missing text field in API response');
        }
    }

    /**
     * Проверяет тип поля text
     */
    private function validateTextFieldType(array $data): void
    {
        if (!is_string($data['text'])) {
            throw new \RuntimeException('Text field is not a string');
        }
    }

    /**
     * Проверяет, что поле text не пустое
     */
    private function validateTextFieldNotEmpty(array $data): void
    {
        if (empty(trim($data['text']))) {
            throw new \RuntimeException('Text field is empty');
        }
    }

    /**
     * Валидирует URL
     */
    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL format');
        }

        $parsedUrl = parse_url($url);
        if (!in_array($parsedUrl['scheme'] ?? '', ['http', 'https'])) {
            throw new \InvalidArgumentException('Only HTTP/HTTPS URLs are supported');
        }
    }

    /**
     * Валидирует HTTP ответ
     */
    private function validateHttpResponse(string $raw): void
    {
        if ($raw === false) {
            throw new \RuntimeException('HTTP request failed');
        }

        if (empty($raw)) {
            throw new \RuntimeException('Empty response from server');
        }
    }

    /**
     * Валидирует JSON
     */
    private function validateJson(string $raw): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON decode error: ' . json_last_error_msg());
        }
    }

    /**
     * Выполняет GET-запрос и декодирует JSON
     */
    private function fetchJson(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
                'header' => [
                    'Accept: application/json',
                    'User-Agent: TextAnalyzer/1.0'
                ]
            ]
        ]);

        $raw = @file_get_contents($url, false, $context);
        if ($raw === false) {
            throw new \RuntimeException('HTTP request failed: ' . $url);
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON response');
        }

        return $decoded;
    }

}


