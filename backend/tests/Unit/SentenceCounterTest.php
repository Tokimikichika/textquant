<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Service\TextAnalyzer;

/** 
 * Тестирует SentenceCounter
 */
class SentenceCounterTest extends TestCase
{
    private TextAnalyzer $textAnalyzer;

    protected function setUp(): void
    {
        $this->textAnalyzer = new TextAnalyzer();
    }

    /**
     * 
     * Тестирует анализ сложного текста
     */
    public function testAnalyzeComplexText(): void
    {
        $text = "The quick brown fox jumps over the lazy dog. This is a test sentence. Another sentence for testing purposes.";
        $result = $this->textAnalyzer->analyze($text, "complex.txt");
        
        $this->assertEquals(19, $result['words']);
    }

    /**
     * 
     * Тестирует анализ текста с множественными абзацами
     */
    public function testAnalyzeTextWithMultipleParagraphs(): void
    {
        $text = "First paragraph.\n\nSecond paragraph.\n\nThird paragraph.";
        $result = $this->textAnalyzer->analyze($text, "paragraphs.txt");
        
        $this->assertEquals(3, $result['paragraphs']);
    }

    /**
     * Тестирует анализ текста с специальными символами
     */
    public function testAnalyzeTextWithSpecialCharacters(): void
    {
        $text = "Hello, world! How are you? I'm fine, thank you.";
        $result = $this->textAnalyzer->analyze($text, "special.txt");
        
        $this->assertEquals(3, $result['sentences']);
    }

    /**
     * Тестирует анализ текста с Unicode символами (кириллица)
     */
    public function testAnalyzeUnicodeText(): void
    {
        $text = "Привет мир! Как дела?";
        $result = $this->textAnalyzer->analyze($text, "unicode.txt");
        
        $this->assertEquals(4, $result['words']);
    }

    /**
     * Тестирует анализ текста с числами
     */
    public function testAnalyzeTextWithNumbers(): void
    {
        $text = "I have 123 apples and 456 oranges.";
        $result = $this->textAnalyzer->analyze($text, "numbers.txt");
        
        $this->assertEquals(7, $result['words']);
    }

    /**
     * Тестирует анализ текста с смешанным содержанием
     */
    public function testAnalyzeTextWithMixedContent(): void
    {
        $text = "Hello world! This is test123. How are you? I'm fine, thank you.";
        $result = $this->textAnalyzer->analyze($text, "mixed.txt");
        
        $this->assertEquals(12, $result['words']);
    }

    /**
     * Тестирует анализ текста с пустыми строками
     */
    public function testAnalyzeTextWithEmptyLines(): void
    {
        $text = "First line.\n\n\nSecond line.";
        $result = $this->textAnalyzer->analyze($text, "empty_lines.txt");
        
        $this->assertEquals(2, $result['paragraphs']);
    }

    /**
     * Тестирует анализ текста с табами и пробелами
     */
    public function testAnalyzeTextWithTabsAndSpaces(): void
    {
        $text = "Hello\tworld.    How are you?";
        $result = $this->textAnalyzer->analyze($text, "tabs.txt");
        
        $this->assertEquals(5, $result['words']);
    }

    /**
     * Тестирует анализ текста с повторяющимися словами
     */
    public function testAnalyzeTextWithRepeatedWords(): void
    {
        $text = "the the quick brown fox the";
        $result = $this->textAnalyzer->analyze($text, "repeated.txt");
        
        $this->assertEquals('the', $result['top_words'][0]['word']);
    }

    /**
     * Тестирует анализ текста с повторяющимися словами
     */
    public function testAnalyzeTextWithRepeatedWordsCount(): void
    {
        $text = "the the quick brown fox the";
        $result = $this->textAnalyzer->analyze($text, "repeated.txt");
        
        $this->assertEquals(3, $result['top_words'][0]['count']);
    }

    /**
     * Тестирует анализ текста с повторяющимися словами
     */
    public function testAnalyzeWithDataProvider(string $text, int $expectedWords, int $expectedSentences): void
    {
        $result = $this->textAnalyzer->analyze($text, "test.txt");
        
        $this->assertEquals($expectedWords, $result['words']);
        $this->assertEquals($expectedSentences, $result['sentences']);
    }

    /**
     * Тестирует анализ текста с повторяющимися словами
     */
    public function complexTextDataProvider(): array
    {
        return [
            'simple sentence' => ['Hello world.', 2, 1],
            'two sentences' => ['Hello world. How are you?', 5, 2],
            'with punctuation' => ['Hello, world! How are you?', 5, 2],
            'with numbers' => ['I have 123 apples.', 4, 1],
            'with special chars' => ['Hello@world.com is my email.', 4, 2],
            'unicode text' => ['Привет мир! Как дела?', 4, 2],
        ];
    }
}

