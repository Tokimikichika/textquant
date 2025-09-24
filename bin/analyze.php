<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\CharacterCounter;
use Tokimikichika\Find\ParagraphCounter;
use Tokimikichika\Find\ResultFormatter;
use Tokimikichika\Find\SentenceCounter;
use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\TextReader;
use Tokimikichika\Find\TopWordAnalyzer;
use Tokimikichika\Find\WordCounter;

/**
 * Показывает справку по использованию скрипта
 */
function showHelp()
{
    echo "Использование:\n";
    echo "  php bin/analyze.php --file=\"путь к файлу\"\n";
    echo "  php bin/analyze.php --text=\"какой-то текст\"\n";
    echo "  php bin/analyze.php -h, --help  Показать эту справку\n";
}

$options = getopt("h", ["file:", "text:", "help"]);

if (isset($options["h"]) || isset($options["help"])) {
    showHelp();
    exit(0);
}

$wordCounter = new WordCounter();
$characterCounter = new CharacterCounter();
$sentenceCounter = new SentenceCounter();
$paragraphCounter = new ParagraphCounter();
$topWordAnalyzer = new TopWordAnalyzer();

$analyzer = new TextAnalyzer(
    $wordCounter,
    $characterCounter,
    $sentenceCounter,
    $paragraphCounter,
    $topWordAnalyzer
);

$formatter = new ResultFormatter();

if (isset($options["file"])) {
    $filePath = $options["file"];
    $textReader = new TextReader();

    try {
        $text = $textReader->readFromFile($filePath);
        $results = $analyzer->analyze($text, basename($filePath));
        echo $formatter->formatResults($results);
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage() . "\n";
        exit(1);
    }

} elseif (isset($options["text"])) {
    $text = $options["text"];
    $results = $analyzer->analyze($text, "text");
    echo $formatter->formatResults($results);

} else {
    echo "Ошибка: Необходимо указать либо --file, либо --text.\n\n";
    showHelp();
    exit(1);
}
