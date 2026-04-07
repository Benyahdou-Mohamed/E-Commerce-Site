<?php

declare(strict_types=1);

namespace App\Controller;

use App\GraphQL\Schema;
use Dotenv\Dotenv;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL as GraphQLBase;
use Throwable;

class GraphQL
{
    public static function handle()
    {
        self::setHeaders();

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return json_encode(['data' => null]);
        }

        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput ?: '{}', true) ?: [];
            $query = $input['query'] ?? '';
            $variableValues = $input['variables'] ?? null;
            $schema = Schema::build();
            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE);
        } catch (Throwable $e) {
            $output = [
                'errors' => [[
                    'message' => $e->getMessage(),
                ]],
            ];
        }

        return json_encode($output);
    }

    private static function setHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET,POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json; charset=UTF-8');
        header('ngrok-skip-browser-warning: true');

        static $loaded = false;
        if (!$loaded && file_exists(__DIR__ . '/../../.env')) {
            Dotenv::createImmutable(__DIR__ . '/../../')->safeLoad();
            $loaded = true;
        }
    }
}
