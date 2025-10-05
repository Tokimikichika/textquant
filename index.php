<?php

require_once __DIR__ . '/vendor/autoload.php';

use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\TextReader;
use Tokimikichika\Find\ResultFormatter;
use Tokimikichika\Find\WordCounter;
use Tokimikichika\Find\CharacterCounter;
use Tokimikichika\Find\SentenceCounter;
use Tokimikichika\Find\ParagraphCounter;
use Tokimikichika\Find\TopWordAnalyzer;
use Tokimikichika\Find\WebController;
use Tokimikichika\Find\ViewRenderer;

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
$textReader = new TextReader();
$viewRenderer = new ViewRenderer();

$controller = new WebController($analyzer, $textReader, $formatter, $viewRenderer);

$data = $controller->handleRequest();

error_log("index.php: Data received: " . json_encode($data));

if (isset($_GET['debug'])) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
}

$html = $viewRenderer->renderWithLayout('home', array_merge($data, [
    'formatter' => $formatter
]));

error_log("index.php: HTML rendered, length: " . strlen($html));
echo $html;
?>
