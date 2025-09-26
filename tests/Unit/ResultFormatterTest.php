<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\ResultFormatter;
    
class ResultFormatterTest extends TestCase
{
    private ResultFormatter $resultFormatter;

    protected function setUp(): void
    {
        $this->resultFormatter = new ResultFormatter();
    }

    public function testFormatNumber(): void
    {
        $this->assertEquals('1,245', $this->resultFormatter->formatNumber(1245));
        $this->assertEquals('1,000,000', $this->resultFormatter->formatNumber(1000000));
    }

    public function testFormatResults(): void
    {
        $results = [
            'source' => 'test.txt',
            'words' => 10,
            'characters' => 50,
            'sentences' => 2,
            'paragraphs' => 1,
            'avg_word_length' => 5.0,
            'avg_sentence_length' => 5.0,
            'top_words' => [
                ['word' => 'test', 'count' => 2],
                ['word' => 'word', 'count' => 1]
            ]
        ];

        $formatted = $this->resultFormatter->formatResults($results);
        
        $this->assertStringContainsString('File: test.txt', $formatted);
        $this->assertStringContainsString('Words: 10', $formatted);
        $this->assertStringContainsString('Characters: 50', $formatted);
        $this->assertStringContainsString('Top 5 words: test (2), word (1)', $formatted);
    }
}

