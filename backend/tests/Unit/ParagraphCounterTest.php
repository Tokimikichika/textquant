<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\ParagraphCounter;

class ParagraphCounterTest extends TestCase
{
    private ParagraphCounter $paragraphCounter;

    protected function setUp(): void
    {
        $this->paragraphCounter = new ParagraphCounter();
    }

    public function testCountParagraphs(): void
    {
        $text = "First paragraph.\n\nSecond paragraph.";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithMultipleNewlines(): void
    {
        $text = "First paragraph.\n\n\nSecond paragraph.\n\nThird paragraph.";
        $this->assertEquals(3, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithEmptyLines(): void
    {
        $text = "First paragraph.\n\n\n\nSecond paragraph.";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithSpacesBetween(): void
    {
        $text = "First paragraph.\n  \nSecond paragraph.";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithTabsBetween(): void
    {
        $text = "First paragraph.\n\t\nSecond paragraph.";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithMixedSeparators(): void
    {
        $text = "First paragraph.\n\n\t\nSecond paragraph.\n\nThird paragraph.";
        $this->assertEquals(3, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithUnicodeText(): void
    {
        $text = "Привет мир.\n\nКак дела?";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithSpecialCharacters(): void
    {
        $text = "Hello@world.com\n\nHow are you?";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithNumbers(): void
    {
        $text = "I have 123 apples.\n\nHow many do you have?";
        $this->assertEquals(2, $this->paragraphCounter->count($text));
    }

    public function testCountParagraphsWithEmptyString(): void
    {
        $this->assertEquals(0, $this->paragraphCounter->count(""));
    }

    public function testCountParagraphsWithOnlyNewlines(): void
    {
        $this->assertEquals(0, $this->paragraphCounter->count("\n\n\n"));
    }

    public function testCountParagraphsWithOnlySpacesAndNewlines(): void
    {
        $this->assertEquals(0, $this->paragraphCounter->count("   \n\n   \n\n   "));
    }

    public function testCountParagraphsWithSingleLine(): void
    {
        $this->assertEquals(1, $this->paragraphCounter->count("Single line."));
    }

    public function testCountParagraphsWithSingleLineAndNewline(): void
    {
        $this->assertEquals(1, $this->paragraphCounter->count("Single line.\n"));
    }

    public function testCountParagraphsWithSingleLineAndMultipleNewlines(): void
    {
        $this->assertEquals(1, $this->paragraphCounter->count("Single line.\n\n\n"));
    }

    public function testCountParagraphsWithTwoLinesNoDoubleNewline(): void
    {
        $this->assertEquals(1, $this->paragraphCounter->count("First line.\nSecond line."));
    }

    public function testCountParagraphsWithTwoLinesWithDoubleNewline(): void
    {
        $this->assertEquals(2, $this->paragraphCounter->count("First line.\n\nSecond line."));
    }

    public function testCountParagraphsWithThreeLinesWithDoubleNewlines(): void
    {
        $this->assertEquals(3, $this->paragraphCounter->count("First.\n\nSecond.\n\nThird."));
    }

    public function testCountParagraphsWithThreeLinesWithMixedNewlines(): void
    {
        $this->assertEquals(3, $this->paragraphCounter->count("First.\n\n\nSecond.\n\nThird."));
    }
}

