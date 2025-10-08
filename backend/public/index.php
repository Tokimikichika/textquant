<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\WordCounter;
use Tokimikichika\Find\CharacterCounter;
use Tokimikichika\Find\SentenceCounter;
use Tokimikichika\Find\ParagraphCounter;
use Tokimikichika\Find\TopWordAnalyzer;
use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\TextReader;
use Tokimikichika\Find\ApiController;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$wordCounter = new WordCounter();
$characterCounter = new CharacterCounter();
$sentenceCounter = new SentenceCounter();
$paragraphCounter = new ParagraphCounter();
$topWordAnalyzer = new TopWordAnalyzer($wordCounter);
$analyzer = new TextAnalyzer(
	$wordCounter,
	$characterCounter,
	$sentenceCounter,
	$paragraphCounter,
	$topWordAnalyzer
);
$api = new ApiController($analyzer);

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET,POST,OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type');
});

$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

$app->post('/api/v1/analyze/text', [$api, 'analyzeText']);

$app->run();