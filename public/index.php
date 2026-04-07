<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Global CORS + JSON headers for all responses (including errors and preflight).
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Return JSON errors for unexpected runtime exceptions.
set_exception_handler(function (Throwable $e): void {
    echo json_encode(['errors' => [['message' => $e->getMessage()]]]);
});

// Skeleton-style router: entrypoint -> controller handler.
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r): void {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
    $r->addRoute('OPTIONS', '/graphql', [App\Controller\GraphQL::class, 'handle']);
    // Keep skeleton /graphql route, but also support project root (/public/) used by frontend env.
    $r->post('/', [App\Controller\GraphQL::class, 'handle']);
    $r->addRoute('OPTIONS', '/', [App\Controller\GraphQL::class, 'handle']);
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
// Remove deployment base path so routes work under nested directories.
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}
$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errors' => [['message' => 'Route not found']]]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errors' => [['message' => 'Method not allowed']]]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        echo $handler();
        break;
}
