<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\TopWordAnalyzer;

class TopWordAnalyzerTest extends TestCase
{
    private TopWordAnalyzer $topWordAnalyzer;

    protected function setUp(): void
    {
        $this->topWordAnalyzer = new TopWordAnalyzer();
    }

    public function testGetTopWords(): void
    {
        $text = "the the quick brown fox";
        $topWords = $this->topWordAnalyzer->getTopWords($text, 3);
        
        $this->assertCount(3, $topWords);
        $this->assertEquals('the', $topWords[0]['word']);
        $this->assertEquals(2, $topWords[0]['count']);
    }

    public function testGetTopWordsWithLimit(): void
    {
        $text = "a b c d e f g h i j";
        $topWords = $this->topWordAnalyzer->getTopWords($text, 5);
        
        $this->assertCount(5, $topWords);
    }

    public function testGetWordFrequencies(): void
    {
        $text = "the the quick";
        $frequencies = $this->topWordAnalyzer->getWordFrequencies($text);
        
        $this->assertEquals(2, $frequencies['the']);
        $this->assertEquals(1, $frequencies['quick']);
    }
}

