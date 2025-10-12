<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Enum\ApiConfig;

class ApiConfigTest extends TestCase
{
    /**
     * Тестирует значение константы FISH_TEXT_API_URL
     * Проверяет, что URL соответствует ожидаемому формату
     */
    public function testFishTextApiUrl(): void
    {
        $this->assertEquals('https://fish-text.ru/get?type=paragraph&number=1&format=json', ApiConfig::FISH_TEXT_API_URL->value);
    }

    /**
     * Тестирует, что ApiConfig является BackedEnum
     * Проверяет корректность наследования от BackedEnum
     */
    public function testApiConfigIsEnum(): void
    {
        $this->assertInstanceOf(\BackedEnum::class, ApiConfig::FISH_TEXT_API_URL);
    }

    /**
     * Тестирует валидность значений ApiConfig
     * Проверяет, что значение является строкой и начинается с https://
     */
    public function testApiConfigValues(): void
    {
        $this->assertIsString(ApiConfig::FISH_TEXT_API_URL->value);
        $this->assertStringStartsWith('https://', ApiConfig::FISH_TEXT_API_URL->value);
    }
}
