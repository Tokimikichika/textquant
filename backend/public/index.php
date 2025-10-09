<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\Controller\ApiController;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\RandomTextService;
use Tokimikichika\Find\Service\WebScraperService;

$app = AppFactory::create();

$analyzer = new TextAnalyzer();
$randomTextService = new RandomTextService();
$webScraperService = new WebScraperService();
$controller = new ApiController($analyzer, $randomTextService, $webScraperService);

$app->post('/api/v1/analyze/text', [$controller, 'analyzeText']);
$app->post('/api/v1/analyze/url', [$controller, 'analyzeUrl']);
$app->get('/api/v1/text/random', [$controller, 'randomText']);

$app->run();