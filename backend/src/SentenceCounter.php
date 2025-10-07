<?php

namespace Tokimikichika\Find;

/**
 * Класс для подсчета предложений в тексте
 *
 */
class SentenceCounter
{
    /**
     * Подсчитывает количество предложений в тексте
     *
     * @param string $text Исходный текст для анализа
     * @return int Количество предложений
     */
    public function count(string $text): int
    {
        return count($this->getSentences($text));
    }

    /**
     * Подсчитывает среднее количество слов в предложении
     *
     * @param string $text Исходный текст для анализа
     * @return float Среднее количество слов в предложении
     */
    public function getAverageLength(string $text): float
    {
        $sentences = $this->getSentences($text);
        if (empty($sentences)) {
            return 0.0;
        }

        $wordCounter = new WordCounter();
        $totalWords = 0;
        foreach ($sentences as $sentence) {
            $totalWords += $wordCounter->count($sentence);
        }

        return round($totalWords / count($sentences), 1);
    }

    /**
     * Извлекает список предложений из текста
     *
     * @param string $text Исходный текст для анализа
     * @return array Список предложений
     */
    private function getSentences(string $text): array
    {
        $sentences = preg_split('/[.!?]+/', $text);
        return array_filter($sentences, function ($sentence) {
            return !empty(trim($sentence));
        });
    }
}
