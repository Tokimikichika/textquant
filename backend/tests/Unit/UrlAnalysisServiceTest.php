<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Exception\InvalidUrlException;
use Tokimikichika\Find\Service\UrlAnalysisService;

/**
 * Тестирует UrlAnalysisService
 */
class UrlAnalysisServiceTest extends TestCase
{
    private UrlAnalysisService $urlAnalysisService;

    protected function setUp(): void
    {
        $analyzer   = $this->createMock(\Tokimikichika\Find\Service\TextAnalyzer::class);
        $webScraper = $this->createMock(\Tokimikichika\Find\Service\WebScraperService::class);

        $this->urlAnalysisService = new UrlAnalysisService($analyzer, $webScraper);
    }

    /**
     * Тестирует валидацию пустого URL
     * Ожидает исключение InvalidArgumentException с сообщением "URL is required"
     */
    public function testAnalyzeUrlWithEmptyUrl(): void
    {
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage('URL is required');

        $this->urlAnalysisService->analyzeUrl('');
    }

    /**
     * Тестирует валидацию URL состоящего только из пробелов
     * Ожидает исключение InvalidArgumentException с сообщением "URL is required"
     */
    public function testAnalyzeUrlWithWhitespaceUrl(): void
    {
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage('URL is required');

        $this->urlAnalysisService->analyzeUrl('   ');
    }

    /**
     * Тестирует вызов WebScraperService при анализе URL
     * Проверяет, что метод scrapeUrl вызывается с правильным URL
     */
    public function testAnalyzeUrlCallsWebScraperService(): void
    {
        $analyzer   = $this->createMock(\Tokimikichika\Find\Service\TextAnalyzer::class);
        $webScraper = $this->createMock(\Tokimikichika\Find\Service\WebScraperService::class);

        $webScraper->expects($this->once())
            ->method('scrapeUrl')
            ->with('https://example.com')
            ->willReturn('Test content for analysis');

        $analyzer->expects($this->once())
            ->method('analyze')
            ->with('Test content for analysis', 'url')
            ->willReturn(['word_count' => 4]);

        $urlAnalysisService = new UrlAnalysisService($analyzer, $webScraper);
        $urlAnalysisService->analyzeUrl('https://example.com');
    }

    /**
     * Тестирует вызов TextAnalyzer при анализе URL
     * Проверяет, что метод analyze вызывается с правильными параметрами
     */
    public function testAnalyzeUrlCallsTextAnalyzer(): void
    {
        $analyzer   = $this->createMock(\Tokimikichika\Find\Service\TextAnalyzer::class);
        $webScraper = $this->createMock(\Tokimikichika\Find\Service\WebScraperService::class);

        $webScraper->method('scrapeUrl')->willReturn('Test content');

        $analyzer->expects($this->once())
            ->method('analyze')
            ->with('Test content', 'url')
            ->willReturn(['word_count' => 4]);

        $urlAnalysisService = new UrlAnalysisService($analyzer, $webScraper);
        $urlAnalysisService->analyzeUrl('https://example.com');
    }

    /**
     * Тестирует возврат массива из метода analyzeUrl
     * Проверяет, что результат является массивом
     */
    public function testAnalyzeUrlReturnsArray(): void
    {
        $analyzer   = $this->createMock(\Tokimikichika\Find\Service\TextAnalyzer::class);
        $webScraper = $this->createMock(\Tokimikichika\Find\Service\WebScraperService::class);

        $webScraper->method('scrapeUrl')->willReturn('Test content');
        $analyzer->method('analyze')->willReturn(['word_count' => 4]);

        $urlAnalysisService = new UrlAnalysisService($analyzer, $webScraper);
        $result             = $urlAnalysisService->analyzeUrl('https://example.com');

        $this->assertIsArray($result);
    }

    /**
     * Тестирует структуру возвращаемого массива из метода analyzeUrl
     * Проверяет наличие всех необходимых ключей в результате
     */
    public function testAnalyzeUrlReturnsCorrectStructure(): void
    {
        $analyzer   = $this->createMock(\Tokimikichika\Find\Service\TextAnalyzer::class);
        $webScraper = $this->createMock(\Tokimikichika\Find\Service\WebScraperService::class);

        $webScraper->method('scrapeUrl')->willReturn('Test content');
        $analyzer->method('analyze')->willReturn([
            'word_count'          => 4,
            'character_count'     => 20,
            'sentence_count'      => 1,
            'paragraph_count'     => 1,
            'avg_word_length'     => 5.0,
            'avg_sentence_length' => 4.0,
            'top_words'           => ['test', 'content'],
            'source'              => 'url',
        ]);

        $urlAnalysisService = new UrlAnalysisService($analyzer, $webScraper);
        $result             = $urlAnalysisService->analyzeUrl('https://example.com');

        $this->assertArrayHasKey('word_count', $result);
        $this->assertArrayHasKey('character_count', $result);
        $this->assertArrayHasKey('sentence_count', $result);
        $this->assertArrayHasKey('paragraph_count', $result);
        $this->assertArrayHasKey('avg_word_length', $result);
        $this->assertArrayHasKey('avg_sentence_length', $result);
        $this->assertArrayHasKey('top_words', $result);
        $this->assertArrayHasKey('source', $result);
    }
}
