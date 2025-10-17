<?php

namespace Tokimikichika\Find\Service;

use Tokimikichika\RandomText\RandomTextGenerator;

class RandomTextService
{
    private RandomTextGenerator $generator;

    public function __construct()
    {
        $this->generator = new RandomTextGenerator();
    }

    public function getRandomText(): string
    {
        return $this->generator->generateParagraph(1);
    }
}
