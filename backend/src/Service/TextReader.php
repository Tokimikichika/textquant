<?php

namespace Tokimikichika\Find\Service;

/**
 * Класс для чтения текста из файла
 */
class TextReader
{
    /**
     * Чтение текста из файла
     *
     * @param string $filePath Путь к файлу
     * @return string Текст
     */
    public function readFromFile(string $filePath): string
    {
        if (!$this->validateFile($filePath)) {
            throw new \InvalidArgumentException("Файл не найден: $filePath");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException("Не удалось прочитать файл: $filePath");
        }

        return $content;
    }

    /**
     * Валидация файла
     *
     * @param string $filePath Путь к файлу
     * @return bool true если файл валиден, false если нет
     */
    public function validateFile(string $filePath): bool
    {
        return file_exists($filePath) && is_file($filePath) && is_readable($filePath);
    }
}


