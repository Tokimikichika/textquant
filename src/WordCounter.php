<?php

namespace Tokimikichika\Find;

/**
 * Класс для подсчета слов в тексте
 * 
 */
class WordCounter
{
    /**
     * Подсчитывает количество слов в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return int Количество слов
     */
    public function count(string $text): int
    {
        return count($this->getWords($text));
    }

    /**
     * Извлекает список слов из текста
     * 
     * @param string $text Исходный текст для анализа
     * @return array Массив очищенных слов
     */
    public function getWords(string $text): array
    {
        $words = preg_split('/[\s,;:]+/u', mb_strtolower($text, 'UTF-8'));
        
        $cleanWords = array_map(function($word) {
            return preg_replace('/[^\p{L}\p{N}]/u', '', $word);
        }, $words);
        
        return array_filter($cleanWords, function($cleanWord) {
            return !empty($cleanWord);
        });
    }

    /**
     * Вычисляет среднюю длину слова в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return float Средняя длина слова
     */
    public function getAverageLength(string $text): float
    {
        $words = $this->getWords($text);
        if (empty($words)) {
            return 0.0;
        }
        $totalLength = 0;
        foreach ($words as $word) {
            $totalLength += mb_strlen($word, 'UTF-8');
        }
        return round($totalLength / count($words), 1);
    }
}
