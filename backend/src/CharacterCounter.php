<?php

namespace Tokimikichika\Find;

/**
 * Класс для подсчета символов в тексте
 *
 */
class CharacterCounter
{
    /**
     * Подсчитывает количество символов в тексте
     *
     * @param string  Исходный текст для анализа
     * @return int Количество символов
     */
    public function count(string $text): int
    {
        return mb_strlen($text, 'UTF-8');
    }
}
