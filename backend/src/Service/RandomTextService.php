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


