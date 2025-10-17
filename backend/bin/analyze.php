<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\Service\ResultFormatter;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\TextReader;

/**
 * Показывает справку по использованию скрипта
 */
function showHelp(): void
{
    echo "Использование:\n";
    echo "  php bin/analyze.php --file=\"путь к файлу\"\n";
    echo "  php bin/analyze.php --text=\"какой-то текст\"\n";
    echo "  php bin/analyze.php -h, --help  Показать эту справку\n";
}

$options = getopt('h', ['file:', 'text:', 'help']);

if (isset($options['h']) || isset($options['help'])) {
    showHelp();
    exit(0);
}

$analyzer = new TextAnalyzer();

$formatter = new ResultFormatter();

if (isset($options['file'])) {
    $filePath   = $options['file'];
    $textReader = new TextReader();

    try {
        $text    = $textReader->readFromFile($filePath);
        $results = $analyzer->analyze($text, basename($filePath));
        echo $formatter->formatResults($results);
    } catch (Exception $e) {
        echo 'Ошибка: ' . $e->getMessage() . "\n";
        exit(1);
    }

} elseif (isset($options['text'])) {
    $text    = $options['text'];
    $results = $analyzer->analyze($text, 'text');
    echo $formatter->formatResults($results);

} else {
    echo "Ошибка: Необходимо указать либо --file, либо --text.\n\n";
    showHelp();
    exit(1);
}
