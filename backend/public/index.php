<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

use Tokimikichika\Find\Controller\ApiController;

$app = AppFactory::create();

$app->post('/api/v1/analyze/text', [new ApiController($analyzer), 'analyzeText']);

$app->run();