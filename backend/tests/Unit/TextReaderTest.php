<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Service\TextReader;

/**
 * Тестирует TextReader
 */
class TextReaderTest extends TestCase
{
    private TextReader $textReader;

    protected function setUp(): void
    {
        $this->textReader = new TextReader();
    }

    /**
     * Тестирует валидацию существующего файла
     */
    public function testValidateFileExistingFile(): void
    {
        $result = $this->textReader->validateFile(__FILE__);
        $this->assertTrue($result);
    }

    /**
     * Тестирует валидацию несуществующего файла
     */
    public function testValidateFileNonExistentFile(): void
    {
        $result = $this->textReader->validateFile('nonexistent.txt');
        $this->assertFalse($result);
    }

    /**
     * Тестирует чтение существующего файла
     */
    public function testReadFromFileExistingFile(): void
    {
        $result = $this->textReader->readFromFile(__FILE__);
        $this->assertStringContainsString('<?php', $result);
    }

    /**
     * Тестирует чтение несуществующего файла
     */
    public function testReadFromFileThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->textReader->readFromFile('nonexistent.txt');
    }

    /**
     * Тестирует чтение директории
     */
    public function testReadFromFileThrowsExceptionForDirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->textReader->readFromFile(__DIR__);
    }
}

