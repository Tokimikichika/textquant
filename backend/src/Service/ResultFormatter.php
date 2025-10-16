<?php

namespace Tokimikichika\Find\Service;

/**
 * Класс для форматирования результатов анализа текста
 *
 */

class ResultFormatter
{
    /**
     * Форматирование результатов анализа текста
     *
     * @param array $results Результаты анализа текста
     * @return string Форматированные результаты
     */
    /**
     * @param array<string,mixed> $results
     */
    public function formatResults(array $results): string
    {
        $file = "File: " . $results['source'] . "\n";
        $dividingStrip = str_repeat("─", 36) . "\n";
        $words = "Words: " . $this->formatNumber($results['words']) . "\n";
        $characters = "Characters: " . $this->formatNumber($results['characters']) . "\n";
        $sentences = "Sentences: " . $this->formatNumber($results['sentences']) . "\n";
        $paragraphs = "Paragraphs: " . $this->formatNumber($results['paragraphs']) . "\n";
        $avgWordLength = "Avg. word length: " . $results['avg_word_length'] . "\n";
        $avgSentenceLength = "Avg. sentence length: " . $results['avg_sentence_length'] . "\n";
        $topWord = "Top 5 words: " . $this->formatTopWords($results['top_words']) . "\n";

        return $file . $dividingStrip . $words . $characters . $sentences . $paragraphs . $avgWordLength . $avgSentenceLength . $topWord;
    }

    /**
     * Форматирование числа
     *
     * @param int $number Число
     * @return string Форматированное число
     */
    public function formatNumber(int $number): string
    {
        return number_format($number, 0, ',', ',');
    }

    /**
     * Форматирование топ слов
     *
     * @param array $topWords Топ слова
     * @return string Форматированные топ слова
     */
    /**
     * @param array<int,array{word:string,count:int}> $topWords
     */
    private function formatTopWords(array $topWords): string
    {
        $formatted = [];
        foreach ($topWords as $wordData) {
            $formatted[] = $wordData['word'] . " (" . $wordData['count'] . ")";
        }
        return implode(", ", $formatted);
    }
}
