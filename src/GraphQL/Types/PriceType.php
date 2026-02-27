<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\TypeRegistry;

class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name'   => 'Price',
            'fields' => function () {       // ← wrap in function
                return [
                    'amount'   => ['type' => Type::float()],
                    'currency' => ['type' => TypeRegistry::currency()],
                ];
            },
        ]);
    }
}