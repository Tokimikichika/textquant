<?php

namespace Tokimikichika\Find\Service;

/**
 * Анализатор текста без внешних зависимостей.
 */
class TextAnalyzer
{

    /**
     * Анализ текста
     */
    public function analyze(string $text, string $source = 'text'): array
    {
        return [
            'source' => $source,
            'words' => $this->countWords($text),
            'characters' => $this->countCharacters($text),
            'sentences' => $this->countSentences($text),
            'paragraphs' => $this->countParagraphs($text),
            'avg_word_length' => $this->getAverageWordLength($text),
            'avg_sentence_length' => $this->getAverageSentenceLength($text),
            'top_words' => $this->getTopWords($text)
        ];
    }

    /**
     * Извлекает список слов из текста
     * 
     * @param string $text Исходный текст для анализа
     * @return array Список слов
     */
    private function getWords(string $text): array
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
     * Подсчитывает количество слов в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return int Количество слов
     */
    private function countWords(string $text): int
    {
        return count($this->getWords($text));
    }

    /**
     * Подсчитывает количество символов в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return int Количество символов
     */
    private function countCharacters(string $text): int
    {
        return mb_strlen($text, 'UTF-8');
    }

    /**
     * Извлекает список предложений из текста
     * 
     * @param string $text Исходный текст для анализа
     * @return array Список предложений
     */
    private function getSentences(string $text): array
    {
        $parts = preg_split('/[.!?]+/u', $text);
        if ($parts === false) {
            return [];
        }
        $sentences = array_map(static fn($s) => trim((string)$s), $parts);
        return array_values(array_filter($sentences, static fn($s) => $s !== ''));
    }

    /**
     * Подсчитывает количество предложений в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return int Количество предложений
     */
    private function countSentences(string $text): int
    {
        return count($this->getSentences($text));
    }

    /**
     * Подсчитывает количество абзацев в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return int Количество абзацев
     */
    private function countParagraphs(string $text): int
    {
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $paragraphs = array_filter($paragraphs, function ($paragraph) {
            return !empty(trim($paragraph));
        });

        return count($paragraphs);
    }

    /**
     * Вычисляет среднюю длину слова в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return float Средняя длина слова
     */
    private function getAverageWordLength(string $text): float
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

    /**
     * Вычисляет среднюю длину предложения в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @return float Средняя длина предложения
     */
    private function getAverageSentenceLength(string $text): float
    {
        $sentences = $this->getSentences($text);
        if (empty($sentences)) {
            return 0.0;
        }

        $totalWords = 0;
        foreach ($sentences as $sentence) {
            $totalWords += $this->countWords($sentence);
        }

        return round($totalWords / count($sentences), 1);
    }

    /**
     * Извлекает список самых частотных слов в тексте
     * 
     * @param string $text Исходный текст для анализа
     * @param int $limit Максимальное количество слов
     * @return array Список самых частотных слов
     */
    private function getTopWords(string $text, int $limit = 5): array
    {
        $words = $this->getWords($text);
        if ($words === []) {
            return [];
        }
        $frequencies = array_count_values($words);
        arsort($frequencies); 
        $limited = array_slice($frequencies, 0, $limit, true);
        $result = [];
        foreach ($limited as $word => $count) {
            $result[] = ['word' => $word, 'count' => $count];
        }
        return $result;
    }
}


