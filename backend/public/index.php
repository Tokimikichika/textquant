<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\Controller\TextController;
use Tokimikichika\Find\Controller\UrlController;
use Tokimikichika\Find\Controller\RandomController;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\RandomTextService;
use Tokimikichika\Find\Service\WebScraperService;

$app = AppFactory::create();

$analyzer = new TextAnalyzer();
$randomTextService = new RandomTextService();
$webScraperService = new WebScraperService();

$textController = new TextController($analyzer);
$urlController = new UrlController($analyzer, $webScraperService);
$randomController = new RandomController($randomTextService);

$app->post('/api/v1/analyze/text', [$textController, 'analyze']);
$app->post('/api/v1/analyze/url', [$urlController, 'analyze']);
$app->get('/api/v1/text/random', [$randomController, 'getText']);

$app->run();