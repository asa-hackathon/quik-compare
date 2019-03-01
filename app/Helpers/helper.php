<?php

namespace App\Helpers;
use Throwable;
use Exception;

class Helper
{
    public static function parseAttribute($object, $path){
        $pathArray = array_filter(explode('.', $path));

        if (empty($pathArray)) {
            return null;
        }

        return array_reduce($pathArray, function ($v1,$v2) {
            return $v1[$v2];
        }, $object);
    }

    public static function evaluateExpression($object, $expression){
        try {
            $computedFunction = create_function('$response', $expression);

            if($computedFunction === false) {
                return null;
            }

            $result = $computedFunction($object);
            
            if(!is_null($result)) {
                return $result;
            } else {
                return null;
            }
        
        } catch(Throwable $e) {
            return null;
        }
    }
}