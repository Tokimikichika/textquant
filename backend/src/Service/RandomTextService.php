<?php

namespace Tokimikichika\Find\Service;

use Tokimikichika\Find\Enum\ApiConfig;
use Tokimikichika\Find\Exception\HttpRequestException;
use Tokimikichika\Find\Exception\InvalidParseException;
use Tokimikichika\Find\Exception\InvalidUrlException;

class RandomTextService
{
    /**
     * Возвращает случайный текст из внешнего источника
     * 
     * @return string Случайный текст
     */
    public function getRandomText(): string
    {
        $data = $this->fetchJson(ApiConfig::FISH_TEXT_API_URL->value);
        $this->validateApiResponse($data);
        
        return $data['text'];
    }

    /**
     * Валидирует ответ от API
     * @param array $data Данные ответа от API
     */
    /**
     * @param array<string,mixed> $data
     */
    private function validateApiResponse(array $data): void
    {
        $this->validateTextFieldExists($data);
        $this->validateTextFieldType($data);
        $this->validateTextFieldNotEmpty($data);
    }

    /**
     * Проверяет наличие поля text в ответе
     * @param array $data Данные ответа от API
     */
    /**
     * @param array<string,mixed> $data
     */
    private function validateTextFieldExists(array $data): void
    {
        if (!isset($data['text'])) {
            throw new \RuntimeException('Missing text field in API response');
        }
    }

    /**
     * Проверяет тип поля text
     * @param array $data Данные ответа от API
     */
    /**
     * @param array<string,mixed> $data
     */
    private function validateTextFieldType(array $data): void
    {
        if (!is_string($data['text'])) {
            throw new \RuntimeException('Text field is not a string');
        }
    }

    /**
     * Проверяет, что поле text не пустое
     * @param array $data Данные ответа от API
     */
    /**
     * @param array<string,mixed> $data
     */
    private function validateTextFieldNotEmpty(array $data): void
    {
        if (empty(trim($data['text']))) {
            throw new \RuntimeException('Text field is empty');
        }
    }

    /**
     * Валидирует URL
     * @param string $url URL для валидации
     */
    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException('Invalid URL format');
        }

        $parsedUrl = parse_url($url);
        if ($parsedUrl === false) {
            throw new InvalidParseException('Failed to parse URL');
        }
        if (!in_array($parsedUrl['scheme'] ?? '', ['http', 'https'])) {
            throw new InvalidUrlException('Only HTTP/HTTPS URLs are supported');
        }
    }

    /**
     * Валидирует HTTP ответ
     * @param string $raw HTTP ответ
     */
    private function validateHttpResponse(string $raw): void
    {
        if (empty($raw)) {
            throw new HttpRequestException('Empty response from server');
        }
    }

    /**
     * Валидирует JSON
     * @param string $raw JSON ответ
     */
    private function validateJson(string $raw): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidParseException('JSON decode error: ' . json_last_error_msg());
        }
    }

    /**
     * Выполняет GET-запрос и декодирует JSON
     * @param string $url URL для запроса
     */
    /**
     * @return array<string,mixed>
     */
    private function fetchJson(string $url): array
    {
        $this->validateUrl($url);
        $raw = $this->makeHttpRequest($url);
        $this->validateHttpResponse($raw);
        return $this->parseJson($raw);
    }

    /**
     * Выполняет HTTP-запрос
     * @param string $url URL для запроса
     */
    private function makeHttpRequest(string $url): string
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

        $result = @file_get_contents($url, false, $context);
        if ($result === false) {
            throw new HttpRequestException('HTTP request failed');
        }
        return $result;
    }

    /**
     * Парсит JSON ответ
     * @param string $raw JSON ответ
     */
    /**
     * @return array<string,mixed>
     */
    private function parseJson(string $raw): array
    {
        $decoded = json_decode($raw, true);
        $this->validateJson($raw);
        
        if (!is_array($decoded)) {
            throw new \RuntimeException('Response is not a JSON object');
        }

        return $decoded;
    }

}


