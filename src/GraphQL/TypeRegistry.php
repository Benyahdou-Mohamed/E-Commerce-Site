<?php

namespace App\GraphQL;

use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\AttributeSetType;
use App\GraphQL\Types\AttributeItemType;
use App\GraphQL\Types\PriceType;
use App\GraphQL\Types\CurrencyType;

class TypeRegistry
{
    private static ?CategoryType $category  = null;
    private static ?ProductType  $product  = null;
    private static ?AttributeSetType $attributeSet = null;
    private static ?AttributeItemType $attributeItem = null;
    private static ?PriceType  $price = null;
    private static ?CurrencyType  $currency = null;

    public static function category(): CategoryType
    {
        if (self::$category === null) {
            self::$category = new CategoryType();
        }
        return self::$category;
    }

    public static function product(): ProductType
    {
        if (self::$product === null) {
            self::$product = new ProductType();
        }
        return self::$product;
    }

    public static function attributeSet(): AttributeSetType
    {
        if (self::$attributeSet === null) {
            self::$attributeSet = new AttributeSetType();
        }
        return self::$attributeSet;
    }

    public static function attributeItem(): AttributeItemType
    {
        if (self::$attributeItem === null) {
            self::$attributeItem = new AttributeItemType();
        }
        return self::$attributeItem;
    }

    public static function price(): PriceType
    {
        if (self::$price === null) {
            self::$price = new PriceType();
        }
        return self::$price;
    }

    public static function currency(): CurrencyType
    {
        if (self::$currency === null) {
            self::$currency = new CurrencyType();
        }
        return self::$currency;
    }
}
