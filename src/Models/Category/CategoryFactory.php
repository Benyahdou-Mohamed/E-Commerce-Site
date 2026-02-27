<?php
namespace App\Models\Category; 

class categoryFactory{
    public static function create(array $data): AbstractCategory
    {
        return new DefaultCategory($data);
    }
    

}