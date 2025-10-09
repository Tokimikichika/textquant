<?php

namespace Tokimikichika\Find\Service;

class RandomTextService
{
    /**
     * Возвращает случайный текст из внешнего источника
     */
    public function get(): string
    {
        $url = "https://fish-text.ru/get?type=paragraph&number=1&format=json";
        $data = $this->fetchJson($url);
        if (is_array($data) && isset($data['text']) && is_string($data['text'])) {
            return $data['text'];
        }

        throw new \RuntimeException('Unexpected response from fish-text.ru');
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


