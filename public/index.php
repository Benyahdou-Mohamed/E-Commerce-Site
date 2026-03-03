<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\GraphQL\Schema;
use GraphQL\GraphQL;
use GraphQL\Error\DebugFlag;



// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('ngrok-skip-browser-warning: true');
header('ngrok-skip-browser-warning:', 2);



set_exception_handler(function (\Throwable $e) {
    header('Content-Type: application/json');
    echo json_encode(['errors' => [['message' => $e->getMessage()]]]);
    exit;
});

set_error_handler(function ($code, $message, $file, $line) {
    header('Content-Type: application/json');
    echo json_encode(['errors' => [['message' => "$message in $file on line $line"]]]);
    exit;
});

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get the request body
$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);

$query     = $input['query']     ?? '';
$variables = $input['variables'] ?? null;

try {
    $schema = Schema::build();
    $result = GraphQL::executeQuery(
        $schema,
        $query,
        null,
        null,
        $variables
    );

    $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE);
} catch (\Throwable $e) {
    $output = [
        'errors' => [
            ['message' => $e->getMessage()]
        ]
    ];
}

echo json_encode($output);
