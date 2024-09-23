<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Container;
use App\ErrorHandler;
use Dotenv\Dotenv;

// Load environment variables
function loadEnvironmentVariables(string $path): void
{
    $dotenv = Dotenv::createImmutable($path);
    $dotenv->load();
}

// Set up the DI Container
function setupContainer(): \DI\Container
{
    return Container::create();
}

// Set up the router and define routes
function setupRouter(AltoRouter $router): void
{
    $router->map('GET', '/categories', 'App\Controller\CategoryController#getCategories');
    $router->map('GET', '/categories/tree', 'App\Controller\CategoryController#getCategoryTree');
    $router->map('GET', '/categories/[*:category_id]', 'App\Controller\CategoryController#getCategoryById');
    $router->map('GET', '/courses', 'App\Controller\CourseController#getCourses');
    $router->map('GET', '/courses/[*:course_id]', 'App\Controller\CourseController#getCourses');
}

// Match the current route and execute the appropriate controller action
function matchRoute(AltoRouter $router, \DI\Container $container): void
{
    $match = $router->match();

    if ($match) {
        list($controller, $action) = explode('#', $match['target']);

        if ($container->has($controller)) {
            $resolvedController = $container->get($controller);

            if (is_callable([$resolvedController, $action])) {
                call_user_func_array([$resolvedController, $action], $match['params']);
            } else {
                ErrorHandler::handleInvalidAction();
            }
        } else {
            ErrorHandler::handleControllerNotFound();
        }
    } else {
        ErrorHandler::handle404();
    }
}

// Main execution flow
function main(string $basePath): void
{
    loadEnvironmentVariables($basePath);
    $container = setupContainer();
    $router = new AltoRouter();
    setupRouter($router);
    matchRoute($router, $container);
}

// Execute with the base path
main(__DIR__ . '/../../');
