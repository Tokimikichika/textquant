<?php

namespace Tokimikichika\Find\Service;

use Tokimikichika\HtmlSanitizer\HtmlSanitizer;
use Tokimikichika\Find\Exception\HttpRequestException;
use Tokimikichika\Find\Exception\InvalidUrlException;

class WebScraperService
{
    private HtmlSanitizer $htmlSanitizer;

    public function __construct()
    {
        $this->htmlSanitizer = HtmlSanitizer::create();
    }

    /**
     * Загружает содержимое веб-страницы и конвертирует в обычный текст
     * @param string $url URL для загрузки
     * @return string Содержимое веб-страницы в виде текста
     */
    public function scrapeUrl(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException('Invalid URL format');
        }

        $parsedUrl = parse_url($url);
        if (!in_array($parsedUrl['scheme'] ?? '', ['http', 'https'])) {
            throw new InvalidUrlException('Only HTTP/HTTPS URLs are supported');
        }

        $html = $this->fetchHtml($url);
        return $this->extractTextFromHtml($html);
    }

    /**
     * Загружает HTML содержимое страницы
     * @param string $url URL для загрузки
     * @return string HTML содержимое страницы
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
            throw new HttpRequestException('Failed to fetch URL: ' . $url);
        }

        return $html;
    }

    /**
     * Извлекает текст из HTML, удаляя теги и нормализуя пробелы
     * @param string $html HTML содержимое страницы
     * @return string Текст из HTML
     */
    private function extractTextFromHtml(string $html): string
    {
        return $this->htmlSanitizer->sanitizeText($html);
    }
}
