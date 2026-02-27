<?php 

namespace App\models\Product;



class ConfigurableProduct extends AbstractProduct
{
    public function canAddToCart(array $selectedAttributes) : bool{
        foreach($this->attributes as $attr){
            if(empty($this->selectedAttributes[$attr["id"]])){
                return 0;
            }
        }
        return 1;
    }
}