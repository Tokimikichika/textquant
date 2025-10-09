<?php

namespace Tokimikichika\Find\Service;

class WebScraperService
{
    /**
     * Загружает содержимое веб-страницы и конвертирует в обычный текст
     */
    public function scrapeUrl(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL format');
        }

        $parsedUrl = parse_url($url);
        if (!in_array($parsedUrl['scheme'] ?? '', ['http', 'https'])) {
            throw new \InvalidArgumentException('Only HTTP/HTTPS URLs are supported');
        }

        $html = $this->fetchHtml($url);
        return $this->extractTextFromHtml($html);
    }

    /**
     * Загружает HTML содержимое страницы
     */
    private function fetchHtml(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
                'header' => [
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]
        ]);

        $html = @file_get_contents($url, false, $context);
        if ($html === false) {
            throw new \RuntimeException('Failed to fetch URL: ' . $url);
        }

        return $html;
    }

    /**
     * Извлекает текст из HTML, удаляя теги и нормализуя пробелы
     */
    private function extractTextFromHtml(string $html): string
    {
        $html = preg_replace('/<(script|style)[^>]*>.*?<\/\1>/is', '', $html);
        
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        $text = strip_tags($html);
        
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\n\s*\n/', "\n\n", $text);
        
        $text = trim($text);
        
        return $text;
    }
}
