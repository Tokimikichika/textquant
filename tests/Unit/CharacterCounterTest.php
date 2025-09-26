<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\CharacterCounter;

class CharacterCounterTest extends TestCase
{
    private CharacterCounter $characterCounter;

    protected function setUp(): void
    {
        $this->characterCounter = new CharacterCounter();
    }

    public function testCountCharacters(): void
    {
        $this->assertEquals(5, $this->characterCounter->count("Hello"));
        $this->assertEquals(11, $this->characterCounter->count("Hello world"));
        $this->assertEquals(0, $this->characterCounter->count(""));
    }

    public function testCountCharactersWithSpaces(): void
    {
        $this->assertEquals(12, $this->characterCounter->count("Hello world!"));
    }
}

