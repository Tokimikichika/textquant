<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\TopWordAnalyzer;

/**
 * @group топ слов анализатор
 */
class TopWordAnalyzerTest extends TestCase
{
    private TopWordAnalyzer $topWordAnalyzer;

    protected function setUp(): void
    {
        $this->topWordAnalyzer = new TopWordAnalyzer();
    }

    public function testGetTopWordsEmptyString(): void
    {
        $result = $this->topWordAnalyzer->getTopWords("");
        $this->assertEquals([], $result);
    }

    public function testGetTopWordsSingleWord(): void
    {
        $result = $this->topWordAnalyzer->getTopWords("hello");
        $this->assertCount(1, $result);
    }

    public function testGetTopWordsWithLimit(): void
    {
        $result = $this->topWordAnalyzer->getTopWords("a b c d e f", 3);
        $this->assertCount(3, $result);
    }

    public function testGetTopWordsFirstWordIsMostFrequent(): void
    {
        $result = $this->topWordAnalyzer->getTopWords("the the quick brown fox");
        $this->assertEquals('the', $result[0]['word']);
    }

    public function testGetTopWordsFirstWordCount(): void
    {
        $result = $this->topWordAnalyzer->getTopWords("the the quick brown fox");
        $this->assertEquals(2, $result[0]['count']);
    }

    public function testGetWordFrequenciesEmptyString(): void
    {
        $result = $this->topWordAnalyzer->getWordFrequencies("");
        $this->assertEquals([], $result);
    }

    public function testGetWordFrequenciesSingleWord(): void
    {
        $result = $this->topWordAnalyzer->getWordFrequencies("hello");
        $this->assertEquals(['hello' => 1], $result);
    }

    public function testGetWordFrequenciesMultipleWords(): void
    {
        $result = $this->topWordAnalyzer->getWordFrequencies("the the quick");
        $this->assertEquals(['the' => 2, 'quick' => 1], $result);
    }

    /**
     * @dataProvider topWordsDataProvider
     */
    public function testGetTopWordsWithDataProvider(string $text, int $limit, int $expectedCount): void
    {
        $result = $this->topWordAnalyzer->getTopWords($text, $limit);
        $this->assertCount($expectedCount, $result);
    }

    public function topWordsDataProvider(): array
    {
        return [
            'empty string' => ['', 5, 0],
            'single word' => ['hello', 5, 1],
            'multiple words' => ['a b c d e f', 3, 3],
            'repeated words' => ['the the quick brown fox', 2, 2],
            'limit larger than words' => ['a b c', 10, 3],
        ];
    }
}

