<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Service\RandomTextService;

/**
 * Тестирует RandomTextService
 */
class RandomTextServiceTest extends TestCase
{
    private RandomTextService $randomTextService;

    protected function setUp(): void
    {
        $this->randomTextService = new RandomTextService();
    }

    /**
     * Тестирует возврат строки из метода getRandomText
     * Проверяет, что результат является непустой строкой
     */
    public function testGetRandomTextReturnsString(): void
    {
        $result = $this->randomTextService->getRandomText();
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    /**
     * Тестирует длину возвращаемого текста
     * Проверяет, что текст содержит достаточно символов для анализа
     */
    public function testGetRandomTextContainsText(): void
    {
        $result = $this->randomTextService->getRandomText();
        $this->assertGreaterThan(10, strlen($result));
    }

    /**
     * Тестирует множественные вызовы getRandomText
     * Проверяет, что каждый вызов возвращает валидный результат
     */
    public function testGetRandomTextMultipleCalls(): void
    {
        $result1 = $this->randomTextService->getRandomText();
        $result2 = $this->randomTextService->getRandomText();

        $this->assertIsString($result1);
        $this->assertIsString($result2);
        $this->assertNotEmpty($result1);
        $this->assertNotEmpty($result2);
    }
}
