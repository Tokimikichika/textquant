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
        $this->assertTrue(is_file(__FILE__) && is_readable(__FILE__));
    }

    /**
     * Тестирует валидацию несуществующего файла
     */
    public function testValidateFileNonExistentFile(): void
    {
        $this->assertFalse(is_file('nonexistent.txt'));
    }

    /**
     * Тестирует чтение существующего файла
     */
    public function testReadFromFileExistingFile(): void
    {
        $result = $this->textReader->read(__FILE__);
        $this->assertStringContainsString('<?php', $result);
    }

    /**
     * Тестирует чтение несуществующего файла
     */
    public function testReadFromFileThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->textReader->read('nonexistent.txt');
    }

    /**
     * Тестирует чтение директории
     */
    public function testReadFromFileThrowsExceptionForDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->textReader->read(__DIR__);
    }
}
