<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\Controller\ApiController;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\RandomTextService;

$app = AppFactory::create();

$analyzer = new TextAnalyzer();
$randomTextService = new RandomTextService();
$controller = new ApiController($analyzer, $randomTextService);
$app->post('/api/v1/analyze/text', [$controller, 'analyzeText']);
$app->get('/api/v1/text/random', [$controller, 'randomText']);

$app->run();