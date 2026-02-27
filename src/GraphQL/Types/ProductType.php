<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\TypeRegistry;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name'   => 'Product',
            'fields' => function () {       // ← wrap in function
                return [
                    'id' =>  Type::string(),
                    'name' => Type::string(),
                    'inStock' =>  Type::boolean(),
                    'gallery'=> Type::listOf(Type::string()),
                    'description'=> Type::string(),
                    'brand' => Type::string(),
                    'category' => Type::string(),
                    'prices'=> Type::listOf(TypeRegistry::price()),
                    'attributes' => Type::listOf(TypeRegistry::attributeSet()),
                ];
            },
        ]);
    }
}
