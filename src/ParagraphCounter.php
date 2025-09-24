<?php

namespace Tokimikichika\Find;

/**
 * Класс для подсчета абзацев в тексте
 *
 */
class ParagraphCounter
{
    /**
     * Подсчитывает количество абзацев в тексте
     *
     * @param string $text Исходный текст для анализа
     * @return int Количество абзацев
     */
    public function count(string $text): int
    {
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $paragraphs = array_filter($paragraphs, function ($paragraph) {
            return !empty(trim($paragraph));
        });

        return count($paragraphs);
    }
}
