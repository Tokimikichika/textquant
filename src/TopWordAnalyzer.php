<?php

namespace Tokimikichika\Find;

/**
 * Класс для анализа топ слов в тексте
 *
 */

class TopWordAnalyzer
{
    /**
     * Извлекает список топ слов из текста
     *
     * @param string $text Исходный текст для анализа
     * @return array Список топ слов
     */
    public function getTopWords(string $text, int $limit = 5): array
    {
        $wordCounts = $this->getWordFrequencies($text);
        $sortedWords = $this->sortByFrequency($wordCounts);
        $topWords = [];
        $count = 0;
        foreach ($sortedWords as $word => $frequency) {
            if ($count >= $limit) {
                break;
            }
            $topWords[] = ['word' => $word, 'count' => $frequency];
            $count++;
        }

        return $topWords;
    }

    /**
     * Извлекает список слов из текста
     *
     * @param string $text Исходный текст для анализа
     * @return array Список слов
     */
    public function getWordFrequencies(string $text): array
    {
        $wordCounter = new WordCounter();
        $words = $wordCounter->getWords($text);
        return array_count_values($words);
    }

    /**
     * Сортирует список слов по частоте
     *
     * @param array $wordCounts Список слов
     * @return array Сортированный список слов
     */
    public function sortByFrequency(array $wordCounts): array
    {
        arsort($wordCounts);
        return $wordCounts;
    }
}
