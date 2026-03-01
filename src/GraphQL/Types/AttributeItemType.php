<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeItemType extends ObjectType
{
    public function __construct()
    {
        parent::__construct(
            [
                'name:' => "AttributeItem",
                'fields' => function () {
                    return [
                    'id' => Type::string(),
                    'displayValue' => Type::string(),
                    'value' => Type::string(),
                    ];
                }
            ]
        );
    }
}
