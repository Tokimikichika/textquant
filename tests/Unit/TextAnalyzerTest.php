<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\WordCounter;
use Tokimikichika\Find\CharacterCounter;
use Tokimikichika\Find\SentenceCounter;
use Tokimikichika\Find\ParagraphCounter;
use Tokimikichika\Find\TopWordAnalyzer;

class TextAnalyzerTest extends TestCase
{
    private TextAnalyzer $textAnalyzer;

    protected function setUp(): void
    {
        $wordCounter = new WordCounter();
        $characterCounter = new CharacterCounter();
        $sentenceCounter = new SentenceCounter();
        $paragraphCounter = new ParagraphCounter();
        $topWordAnalyzer = new TopWordAnalyzer();

        $this->textAnalyzer = new TextAnalyzer(
            $wordCounter,
            $characterCounter,
            $sentenceCounter,
            $paragraphCounter,
            $topWordAnalyzer
        );
    }

    public function testAnalyze(): void
    {
        $text = "Hello world. This is test!"; 

        $results = $this->textAnalyzer->analyze($text, "test.txt");

        $this->assertEquals('test.txt', $results['source']);
        $this->assertEquals(5, $results['words']);
        $this->assertEquals(26, $results['characters']);
        $this->assertEquals(2, $results['sentences']);
        $this->assertEquals(1, $results['paragraphs']);
        $this->assertIsFloat($results['avg_word_length']);
        $this->assertIsFloat($results['avg_sentence_length']);
        $this->assertIsArray($results['top_words']);
    }

    public function testAnalyzeEmptyText(): void
    {
        $results = $this->textAnalyzer->analyze("", "empty.txt");

        $this->assertEquals(0, $results['words']);
        $this->assertEquals(0, $results['characters']);
        $this->assertEquals(0, $results['sentences']);
        $this->assertEquals(0, $results['paragraphs']);
    }
}

