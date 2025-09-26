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
        
        $text2 = "Single paragraph.";
        $this->assertEquals(1, $this->paragraphCounter->count($text2));
        
        $this->assertEquals(0, $this->paragraphCounter->count(""));
    }

    public function testCountParagraphsWithMultipleNewlines(): void
    {
        $text = "First paragraph.\n\n\nSecond paragraph.\n\nThird paragraph.";
        $this->assertEquals(3, $this->paragraphCounter->count($text));
    }
}

