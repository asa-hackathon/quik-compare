<?php

namespace App\Services;


use App\Mapping;

class ProductService
{

    public static function getProductById($subCatId, $id)
    {
        $mapping = Mapping::where(['sub_cat_id' => $subCatId])->first();

        if (!isset($mapping)) {
            return [];
        }

        var_dump($mapping);
    }

}