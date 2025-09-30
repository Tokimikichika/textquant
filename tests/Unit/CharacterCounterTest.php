<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\CharacterCounter;

/**
 * @group character-counter
 */
class CharacterCounterTest extends TestCase
{
    private CharacterCounter $characterCounter;

    protected function setUp(): void
    {
        $this->characterCounter = new CharacterCounter();
    }

    public function testCountEmptyString(): void
    {
        $result = $this->characterCounter->count("");
        $this->assertEquals(0, $result);
    }

    public function testCountSingleCharacter(): void
    {
        $result = $this->characterCounter->count("a");
        $this->assertEquals(1, $result);
    }

    public function testCountWord(): void
    {
        $result = $this->characterCounter->count("Hello");
        $this->assertEquals(5, $result);
    }

    public function testCountWordWithSpace(): void
    {
        $result = $this->characterCounter->count("Hello world");
        $this->assertEquals(11, $result);
    }

    public function testCountWordWithPunctuation(): void
    {
        $result = $this->characterCounter->count("Hello world!");
        $this->assertEquals(12, $result);
    }

    public function testCountMultilineText(): void
    {
        $result = $this->characterCounter->count("Hello\nworld");
        $this->assertEquals(11, $result);
    }

    public function testCountTextWithSpecialCharacters(): void
    {
        $result = $this->characterCounter->count("Hello, world! 123");
        $this->assertEquals(17, $result);
    }

    public function testCountUnicodeCharacters(): void
    {
        $result = $this->characterCounter->count("Привет мир");
        $this->assertEquals(10, $result);
    }

    /**
     * @dataProvider characterCountDataProvider
     */
    public function testCountWithDataProvider(string $text, int $expectedCount): void
    {
        $result = $this->characterCounter->count($text);
        $this->assertEquals($expectedCount, $result);
    }

    public function characterCountDataProvider(): array
    {
        return [
            'empty string' => ['', 0],
            'single character' => ['a', 1],
            'word' => ['Hello', 5],
            'two words' => ['Hello world', 11],
            'with punctuation' => ['Hello!', 6],
            'with spaces' => ['  Hello  ', 9],
            'numbers' => ['123', 3],
            'mixed content' => ['Hello 123!', 10],
            'newlines' => ["Hello\nworld", 11],
            'tabs' => ["Hello\tworld", 11],
            'unicode' => ['Привет', 6],
            'emoji' => ['Hello 👋', 7],
        ];
    }
}

