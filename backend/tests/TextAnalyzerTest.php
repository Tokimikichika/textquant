<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\WordCounter;
use Tokimikichika\Find\CharacterCounter;
use Tokimikichika\Find\SentenceCounter;
use Tokimikichika\Find\ParagraphCounter;
use Tokimikichika\Find\TopWordAnalyzer;

/**
 * Класс для тестирования TextAnalyzer
 * 
 * Тестирует все основные функции анализа текста
 */
class TextAnalyzerTest
{
    private $testCount = 0;
    private $passedTests = 0;

    /**
     * Создает TextAnalyzer с внедренными зависимостями
     * 
     * @return TextAnalyzer
     */
    private function createTextAnalyzer(): TextAnalyzer
    {
        $wordCounter = new WordCounter();
        $characterCounter = new CharacterCounter();
        $sentenceCounter = new SentenceCounter();
        $paragraphCounter = new ParagraphCounter();
        $topWordAnalyzer = new TopWordAnalyzer();
        
        return new TextAnalyzer(
            $wordCounter,
            $characterCounter,
            $sentenceCounter,
            $paragraphCounter,
            $topWordAnalyzer
        );
    }

    /**
     * Запускает все тесты
     */
    public function runAllTests()
    {
        echo "Запуск тестов TextAnalyzer...\n\n";
        
        $this->testWordCount();
        $this->testCharacterCount();
        $this->testSentenceCount();
        $this->testParagraphCount();
        $this->testAverageWordLength();
        $this->testAverageSentenceLength();
        $this->testTopWords();
        $this->testEmptyText();
        
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "Результаты тестов: {$this->passedTests}/{$this->testCount} прошли успешно\n";
        
        if ($this->passedTests === $this->testCount) {
            echo "✅ Все тесты прошли успешно!\n";
        } else {
            echo "❌ Некоторые тесты не прошли.\n";
        }
    }

    /**
     * Проверяет условие и увеличивает счетчики тестов
     * 
     * @param bool $condition Условие для проверки
     * @param string $message Сообщение о тесте
     */
    private function assert($condition, $message)
    {
        $this->testCount++;
        if ($condition) {
            $this->passedTests++;
            echo "✅ {$message}\n";
        } else {
            echo "❌ {$message}\n";
        }
    }

    /**
     * Тестирует подсчет слов в тексте
     */
    private function testWordCount()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("Hello world test", "text");
        $this->assert($result["words"] === 3, "Подсчет слов: 3 слова");
        
        $result2 = $analyzer->analyze("One two three four five", "text");
        $this->assert($result2["words"] === 5, "Подсчет слов: 5 слов");
    }

    /**
     * Тестирует подсчет символов в тексте
     */
    private function testCharacterCount()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("Hello", "text");
        $this->assert($result["characters"] === 5, "Подсчет символов: 5 символов");
        
        $result2 = $analyzer->analyze("Hello world", "text");
        $this->assert($result2["characters"] === 11, "Подсчет символов: 11 символов");
    }

    /**
     * Тестирует подсчет предложений в тексте
     */
    private function testSentenceCount()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("Hello world. This is a test!", "text");
        $this->assert($result["sentences"] === 2, "Подсчет предложений: 2 предложения");
        
        $result2 = $analyzer->analyze("One. Two? Three!", "text");
        $this->assert($result2["sentences"] === 3, "Подсчет предложений: 3 предложения");
    }

    /**
     * Тестирует подсчет абзацев в тексте
     */
    private function testParagraphCount()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $text = "First paragraph.\n\nSecond paragraph.";
        $result = $analyzer->analyze($text, "text");
        $this->assert($result["paragraphs"] === 2, "Подсчет абзацев: 2 абзаца");
    }

    /**
     * Тестирует вычисление средней длины слова
     */
    private function testAverageWordLength()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("a bb ccc dddd", "text");
        $this->assert($result["avg_word_length"] === 2.5, "Средняя длина слова: 2.5");
    }

    /**
     * Тестирует вычисление средней длины предложения
     */
    private function testAverageSentenceLength()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("Hello world. This is a test sentence with more words.", "text");
        $this->assert($result["avg_sentence_length"] === 5.0, "Средняя длина предложения: 5.0");
    }

    /**
     * Тестирует анализ топ слов
     */
    private function testTopWords()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("the the quick brown fox", "text");
        $this->assert($result["top_words"][0]["word"] === "the", "Топ слово: 'the'");
        $this->assert($result["top_words"][0]["count"] === 2, "Количество 'the': 2");
    }

    /**
     * Тестирует обработку пустого текста
     */
    private function testEmptyText()
    {
        $analyzer = $this->createTextAnalyzer();
        
        $result = $analyzer->analyze("", "text");
        $this->assert($result["words"] === 0, "Пустой текст: 0 слов");
        $this->assert($result["characters"] === 0, "Пустой текст: 0 символов");
        $this->assert($result["sentences"] === 0, "Пустой текст: 0 предложений");
        $this->assert($result["paragraphs"] === 0, "Пустой текст: 0 абзацев");
    }
}

$test = new TextAnalyzerTest();
$test->runAllTests();