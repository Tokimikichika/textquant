<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Service\WebScraperService;

/**
 * Тестирует WebScraperService
 */
class WebScraperServiceTest extends TestCase
{
    private WebScraperService $webScraperService;

    protected function setUp(): void
    {
        $this->webScraperService = new WebScraperService();
    }

    /**
     * Тестирует валидацию некорректного URL
     * Ожидает исключение InvalidArgumentException с сообщением "Invalid URL format"
     */
    public function testScrapeUrlWithInvalidUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL format');
        
        $this->webScraperService->scrapeUrl('invalid-url');
    }

    /**
     * Тестирует валидацию не-HTTP URL
     * Ожидает исключение InvalidArgumentException с сообщением "Only HTTP/HTTPS URLs are supported"
     */
    public function testScrapeUrlWithNonHttpUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only HTTP/HTTPS URLs are supported');
        
        $this->webScraperService->scrapeUrl('ftp://example.com');
    }

    /**
     * Тестирует успешное получение контента по HTTPS URL
     * Проверяет, что возвращается непустая строка
     */
    public function testScrapeUrlWithValidUrl(): void
    {
        try {
            $result = $this->webScraperService->scrapeUrl('https://httpbin.org/html');
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        } catch (\RuntimeException $e) {
            $this->markTestSkipped('No internet connection available');
        }
    }

    /**
     * Тестирует получение контента по HTTPS URL
     * Проверяет корректность работы с HTTPS протоколом
     */
    public function testScrapeUrlWithHttpsUrl(): void
    {
        try {
            $result = $this->webScraperService->scrapeUrl('https://httpbin.org/html');
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        } catch (\RuntimeException $e) {
            $this->markTestSkipped('No internet connection available');
        }
    }

    /**
     * Тестирует получение контента по HTTP URL
     * Проверяет корректность работы с HTTP протоколом
     */
    public function testScrapeUrlWithHttpUrl(): void
    {
        try {
            $result = $this->webScraperService->scrapeUrl('http://httpbin.org/html');
            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        } catch (\RuntimeException $e) {
            $this->markTestSkipped('No internet connection available');
        }
    }
}
