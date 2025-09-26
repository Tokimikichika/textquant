<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\WordCounter;

class WordCounterTest extends TestCase
{
    private WordCounter $wordCounter;

    protected function setUp(): void
    {
        $this->wordCounter = new WordCounter();
    }

    public function testCountWords(): void
    {
        $this->assertEquals(3, $this->wordCounter->count("Hello world test"));
        $this->assertEquals(5, $this->wordCounter->count("One two three four five"));
        $this->assertEquals(0, $this->wordCounter->count(""));
    }

    public function testGetWords(): void
    {
        $words = $this->wordCounter->getWords("Hello, world! Test123");
        $this->assertEquals(['hello', 'world', 'test123'], $words);
    }

    public function testGetAverageLength(): void
    {
        $this->assertEquals(2.5, $this->wordCounter->getAverageLength("a bb ccc dddd"));
        $this->assertEquals(0.0, $this->wordCounter->getAverageLength(""));
    }

    public function testGetWordsWithPunctuation(): void
    {
        $words = $this->wordCounter->getWords("Hello, world! How are you?");
        $this->assertEquals(['hello', 'world', 'how', 'are', 'you'], $words);
    }
}

