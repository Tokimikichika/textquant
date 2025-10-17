<?php

namespace Tokimikichika\Find\Service;

use Tokimikichika\TextAnalysis\TextAnalyzer as ExternalTextAnalyzer;

/**
 * Адаптер к внешней библиотеке анализа текста
 */
class TextAnalyzer extends ExternalTextAnalyzer
{
    /**
     * Обёртка для совместимости текущего API backend
     * @return array<string,mixed>
     */
    public function analyze(string $text, string $source = 'text'): array
    {
        return [
            'source'     => $source,
            'words'      => $this->countWords($text),
            'characters' => $this->countCharacters($text, true),
            'sentences'  => $this->countSentences($text),
            'paragraphs' => $this->countParagraphs($text),
        ];
    }
}
