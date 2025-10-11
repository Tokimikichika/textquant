<?php

namespace Tokimikichika\Find\Service;

class UrlAnalysisService
{
    public function __construct(
        private TextAnalyzer $analyzer,
        private WebScraperService $webScraperService
    ) {
    }

    /**
     * Анализирует содержимое веб-страницы по URL
     */
    public function analyzeUrl(string $url): array
    {
        $this->validateUrl($url);
        $text = $this->webScraperService->scrapeUrl($url);
        return $this->analyzer->analyze($text, 'url');
    }

    /**
     * Валидирует URL
     */
    private function validateUrl(string $url): void
    {
        if (empty(trim($url))) {
            throw new \InvalidArgumentException('URL is required');
        }
    }
}
