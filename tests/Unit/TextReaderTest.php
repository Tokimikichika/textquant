<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\TextReader;

class TextReaderTest extends TestCase
{
    private TextReader $textReader;

    protected function setUp(): void
    {
        $this->textReader = new TextReader();
    }

    public function testValidateFile(): void
    {
        $this->assertTrue($this->textReader->validateFile(__FILE__));
        $this->assertFalse($this->textReader->validateFile('nonexistent.txt'));
    }

    public function testReadFromFile(): void
    {
        $content = $this->textReader->readFromFile(__FILE__);
        $this->assertStringContainsString('<?php', $content);
    }

    public function testReadFromFileThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->textReader->readFromFile('nonexistent.txt');
    }
}

