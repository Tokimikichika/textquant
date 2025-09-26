<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\SentenceCounter;

class SentenceCounterTest extends TestCase
{
    private SentenceCounter $sentenceCounter;

    protected function setUp(): void
    {
        $this->sentenceCounter = new SentenceCounter();
    }

    public function testCountSentences(): void
    {
        $this->assertEquals(2, $this->sentenceCounter->count("Hello world. This is a test!"));
        $this->assertEquals(3, $this->sentenceCounter->count("One. Two? Three!"));
        $this->assertEquals(0, $this->sentenceCounter->count(""));
    }

    public function testGetAverageLength(): void
    {
        $text = "Hello world. This is a test sentence with more words.";
        $this->assertEquals(5.0, $this->sentenceCounter->getAverageLength($text));
    }

    public function testGetAverageLengthEmpty(): void
    {
        $this->assertEquals(0.0, $this->sentenceCounter->getAverageLength(""));
    }
}

