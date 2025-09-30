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

    public function testCountOnlyPunctuation(): void
    {
        $result = $this->wordCounter->count("!@#$%^&*()");
        $this->assertEquals(0, $result);
    }

    public function testCountOnlySpaces(): void
    {
        $result = $this->wordCounter->count("   \t\n  ");
        $this->assertEquals(0, $result);
    }

    public function testCountOnlyNumbers(): void
    {
        $result = $this->wordCounter->count("123 456 789");
        $this->assertEquals(3, $result);
    }

    public function testCountMixedNumbersAndLetters(): void
    {
        $result = $this->wordCounter->count("test123 word456");
        $this->assertEquals(2, $result);
    }

    public function testCountWordsWithApostrophes(): void
    {
        $result = $this->wordCounter->count("I'm don't won't");
        $this->assertEquals(3, $result);
    }

    public function testCountWordsWithHyphens(): void
    {
        $result = $this->wordCounter->count("well-known state-of-the-art");
        $this->assertEquals(2, $result);
    }

    public function testCountWordsWithUnderscores(): void
    {
        $result = $this->wordCounter->count("test_word another_word");
        $this->assertEquals(2, $result);
    }

    public function testCountWordsWithDots(): void
    {
        $result = $this->wordCounter->count("www.example.com");
        $this->assertEquals(1, $result);
    }

    public function testCountWordsWithMixedSeparators(): void
    {
        $result = $this->wordCounter->count("word1,word2;word3:word4");
        $this->assertEquals(4, $result);
    }

    public function testGetWordsOnlyPunctuation(): void
    {
        $result = $this->wordCounter->getWords("!@#$%^&*()");
        $this->assertEquals([], $result);
    }

    public function testGetWordsOnlySpaces(): void
    {
        $result = $this->wordCounter->getWords("   \t\n  ");
        $this->assertEquals([], $result);
    }

    public function testGetWordsOnlyNumbers(): void
    {
        $result = $this->wordCounter->getWords("123 456 789");
        $this->assertEquals(['123', '456', '789'], $result);
    }

    public function testGetWordsMixedNumbersAndLetters(): void
    {
        $result = $this->wordCounter->getWords("test123 word456");
        $this->assertEquals(['test123', 'word456'], $result);
    }

    public function testGetWordsWithApostrophes(): void
    {
        $result = $this->wordCounter->getWords("I'm don't won't");
        $this->assertEquals(['im', 'dont', 'wont'], $result);
    }

    public function testGetWordsWithHyphens(): void
    {
        $result = $this->wordCounter->getWords("well-known state-of-the-art");
        $this->assertEquals(['wellknown', 'stateoftheart'], $result);
    }

    public function testGetWordsWithUnderscores(): void
    {
        $result = $this->wordCounter->getWords("test_word another_word");
        $this->assertEquals(['testword', 'anotherword'], $result);
    }

    public function testGetWordsWithDots(): void
    {
        $result = $this->wordCounter->getWords("www.example.com");
        $this->assertEquals(['wwwexamplecom'], $result);
    }

    public function testGetWordsWithMixedSeparators(): void
    {
        $result = $this->wordCounter->getWords("word1,word2;word3:word4");
        $this->assertEquals(['word1', 'word2', 'word3', 'word4'], $result);
    }

    public function testGetAverageLengthOnlyPunctuation(): void
    {
        $result = $this->wordCounter->getAverageLength("!@#$%^&*()");
        $this->assertEquals(0.0, $result);
    }

    public function testGetAverageLengthOnlySpaces(): void
    {
        $result = $this->wordCounter->getAverageLength("   \t\n  ");
        $this->assertEquals(0.0, $result);
    }

    public function testGetAverageLengthOnlyNumbers(): void
    {
        $result = $this->wordCounter->getAverageLength("123 456 789");
        $this->assertEquals(3.0, $result);
    }

    public function testGetAverageLengthMixedNumbersAndLetters(): void
    {
        $result = $this->wordCounter->getAverageLength("test123 word456");
        $this->assertEquals(7.0, $result);
    }

    public function testGetAverageLengthWithApostrophes(): void
    {
        $result = $this->wordCounter->getAverageLength("I'm don't won't");
        $this->assertEquals(3.3, $result);
    }

    public function testGetAverageLengthWithHyphens(): void
    {
        $result = $this->wordCounter->getAverageLength("well-known state-of-the-art");
        $this->assertEquals(11.0, $result);
    }

    public function testGetAverageLengthWithUnderscores(): void
    {
        $result = $this->wordCounter->getAverageLength("test_word another_word");
        $this->assertEquals(9.5, $result);
    }

    public function testGetAverageLengthWithDots(): void
    {
        $result = $this->wordCounter->getAverageLength("www.example.com");
        $this->assertEquals(13.0, $result);
    }

    public function testGetAverageLengthWithMixedSeparators(): void
    {
        $result = $this->wordCounter->getAverageLength("word1,word2;word3:word4");
        $this->assertEquals(5.0, $result);
    }
}

