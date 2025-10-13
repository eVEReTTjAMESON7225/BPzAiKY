<?php
// 代码生成时间: 2025-10-14 02:45:19
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// Create Slim app instance
AppFactory::setCodingStyle(array(
    'namespace' => 'App',
    'app_path' => __DIR__ . '/src'
));
$app = AppFactory::create();

// Define routes
$app->get('/', function (Request \$request, Response \$response, array \$args) {
    \$response->getBody()->write('Edge Compute Framework Home');
    return \$response;
});

// Error handling
$app->addErrorMiddleware(true, true, true);

// Run application
$app->run();

/**
 * src/Middleware/ErrorMiddleware.php
 */
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    public function process(Request \$request, RequestHandler \$handler): Response
    {
        try {
            return \$handler->handle(\$request);
        } catch (Throwable \$exception) {
            \$response = \$handler->handle(\$request);
            \$response->getBody()->write('An error occurred: ' . \$exception->getMessage());
            return \$response->withStatus(500);
        }
    }
}

/**
 * src/Controllers/HomeController.php
 */
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    public function home(Request \$request, Response \$response, array \$args): Response
    {
        \$response->getBody()->write('Welcome to the Edge Compute Framework!');
        return \$response;
    }
}
