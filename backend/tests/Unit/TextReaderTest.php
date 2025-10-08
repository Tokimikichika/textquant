<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Service\TextReader;

/**
 * @group text-reader
 */
class TextReaderTest extends TestCase
{
    private TextReader $textReader;

    protected function setUp(): void
    {
        $this->textReader = new TextReader();
    }

    public function testValidateFileExistingFile(): void
    {
        $result = $this->textReader->validateFile(__FILE__);
        $this->assertTrue($result);
    }

    public function testValidateFileNonExistentFile(): void
    {
        $result = $this->textReader->validateFile('nonexistent.txt');
        $this->assertFalse($result);
    }

    public function testReadFromFileExistingFile(): void
    {
        $result = $this->textReader->readFromFile(__FILE__);
        $this->assertStringContainsString('<?php', $result);
    }

    public function testReadFromFileThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->textReader->readFromFile('nonexistent.txt');
    }

    public function testReadFromFileThrowsExceptionForDirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->textReader->readFromFile(__DIR__);
    }
}

