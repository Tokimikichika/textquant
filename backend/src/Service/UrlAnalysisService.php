<?php

namespace Tokimikichika\Find\Service;

/**
 * Сервис для анализа содержимого веб-страницы по URL
 */
class UrlAnalysisService
{
    public function __construct(
        private TextAnalyzer $analyzer,
        private WebScraperService $webScraperService
    ) {
    }

    /**
     * Анализирует содержимое веб-страницы по URL
     * @param string $url URL для анализа
     * @return array Результат анализа
     */
    /**
     * @return array<string,mixed>
     */
    public function analyzeUrl(string $url): array
    {
        $this->validateUrl($url);
        $text = $this->webScraperService->scrapeUrl($url);
        return $this->analyzer->analyze($text, 'url');
    }

    /**
     * Валидирует URL
     * @param string $url URL для валидации
     */
    private function validateUrl(string $url): void
    {
        if (empty(trim($url))) {
            throw new \InvalidArgumentException('URL is required');
        }
    }
}
