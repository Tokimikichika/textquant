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
use Tokimikichika\Find\ResultFormatter;
use Tokimikichika\Find\ViewRenderer;
use Tokimikichika\Find\WebController;

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
$formatter = new ResultFormatter();
$textReader = new TextReader();
$viewRenderer = new ViewRenderer();
$controller = new WebController($analyzer, $textReader, $formatter, $viewRenderer);

$app->get('/', function (Request $request, Response $response) use ($controller, $viewRenderer, $formatter) {
	$data = $controller->handleRequest(); 
	$html = $viewRenderer->renderWithLayout('home', array_merge($data, ['formatter' => $formatter]));
	$response->getBody()->write($html);
	return $response;
});

$app->post('/', function (Request $request, Response $response) use ($controller, $viewRenderer, $formatter) {
	$data = $controller->handleRequest();
	$html = $viewRenderer->renderWithLayout('home', array_merge($data, ['formatter' => $formatter]));
	$response->getBody()->write($html);
	return $response;
});

$app->run();