<?php

namespace App\GraphQL;

use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Resolvers\OrderResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Schema as GraphQLSchema;

class Schema
{
    public static function build(): GraphQLSchema
    {
        // ----------------------------------------
        // Input type for order items
        // ----------------------------------------
        $orderItemInput = new InputObjectType([
            'name'   => 'OrderItemInput',
            'fields' => [
                'productId'          => ['type' => Type::nonNull(Type::string())],
                'quantity'           => ['type' => Type::nonNull(Type::int())],
                'selectedAttributes' => ['type' => Type::string()],
            ],
        ]);

        // ----------------------------------------
        // Query type
        // ----------------------------------------
        $queryType = new ObjectType([
            'name'   => 'Query',
            'fields' => [

                // Get all categories
                'categories' => [
                    'type'    => Type::listOf(TypeRegistry::category()),
                    'resolve' => fn() => CategoryResolver::getAll(),
                ],

                // Get all products or filter by category
                'products' => [
                    'type' => Type::listOf(TypeRegistry::product()),
                    'args' => [
                        'category' => ['type' => Type::string()],
                    ],
                    'resolve' => fn($root, $args) =>
                        ProductResolver::getAll($args['category'] ?? null),
                ],

                // Get single product by id
                'product' => [
                    'type' => TypeRegistry::product(),
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::string())],
                    ],
                    'resolve' => fn($root, $args) =>
                        ProductResolver::getById($args['id']),
                ],

            ],
        ]);

        // ----------------------------------------
        // Mutation type
        // ----------------------------------------
        $mutationType = new ObjectType([
            'name'   => 'Mutation',
            'fields' => [

                // Place an order
                'placeOrder' => [
                    'type' => Type::boolean(),
                    'args' => [
                        'items' => [
                            'type' => Type::nonNull(
                                Type::listOf($orderItemInput)
                            ),
                        ],
                    ],
                    'resolve' => fn($root, $args) =>
                        OrderResolver::createOrder($args['items']),
                ],

            ],
        ]);

        // ----------------------------------------
        // Build and return schema
        // ----------------------------------------
        return new GraphQLSchema([
            'query'    => $queryType,
            'mutation' => $mutationType,
        ]);
    }
}