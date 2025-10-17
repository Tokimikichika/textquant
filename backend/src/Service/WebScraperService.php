<?php

namespace Tokimikichika\Find\Service;

use Tokimikichika\Find\Exception\InvalidUrlException;
use Tokimikichika\HtmlParser\HtmlParser;
use Tokimikichika\HtmlSanitizer\HtmlSanitizer;

class WebScraperService
{
    private HtmlSanitizer $htmlSanitizer;
    private HtmlParser $htmlParser;

    public function __construct()
    {
        $this->htmlSanitizer = HtmlSanitizer::create();
        $this->htmlParser    = new HtmlParser();
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

        $html = $this->htmlParser->fetch($url);

        return $this->extractTextFromHtml($html);
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
