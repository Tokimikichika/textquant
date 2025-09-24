<?php

namespace Tokimikichika\Find;

/**
 *
 * Координирует работу всех счетчиков для анализа текста
 */
class TextAnalyzer
{
    public function __construct(
        private WordCounter $wordCounter,
        private CharacterCounter $characterCounter,
        private SentenceCounter $sentenceCounter,
        private ParagraphCounter $paragraphCounter,
        private TopWordAnalyzer $topWordAnalyzer
    ) {
    }

    /**
     * Анализ текста
     *
     * @param string $text Текст для анализа
     * @param string $source Источник текста (имя файла или "text")
     * @return array Результат анализа
     */
    public function analyze(string $text, string $source = 'text'): array
    {
        return [
            'source' => $source,
            'words' => $this->wordCounter->count($text),
            'characters' => $this->characterCounter->count($text),
            'sentences' => $this->sentenceCounter->count($text),
            'paragraphs' => $this->paragraphCounter->count($text),
            'avg_word_length' => $this->wordCounter->getAverageLength($text),
            'avg_sentence_length' => $this->sentenceCounter->getAverageLength($text),
            'top_words' => $this->topWordAnalyzer->getTopWords($text)
        ];
    }
}
