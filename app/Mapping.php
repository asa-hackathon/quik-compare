<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;
use Exception;
use Log;

class Mapping extends Model
{
    protected $table = "mapping";

    public static function trasformAttributes($subCatId, $response) {
        try {
            $returnData = [];
            $configData = Mapping::where(['sub_cat_id' => $subCatId])
                                ->select(['config'])
                                ->first();
            if (is_null($configData)) {
                return [];
            }

            $jsonConfigData = json_decode($configData->config, true);
            // $jsonConfigData = Mapping::modelMockData();
            // dd($jsonConfigData);
            foreach ($jsonConfigData as $value) {
                if (isset($value['attribute'])) {
                    $returnData[$value['displayName']] = Helper::parseAttribute($response, $value['attribute']);
                } elseif (isset($value['expression'])) {
                    $returnData[$value['displayName']] = Helper::evaluateExpression($response, $value['expression']);
                }
            }

            // dd($returnData);
            return $returnData;
        } catch (\Throwable $e) {
            Log::error('Error occurred in ' . __METHOD__ . ' : ' . $e->getMessage());
            return [];
        }
    }

    private static function modelMockData() {
        return json_decode(
            '[
              {
                "displayName": "Product Name",
                "attribute": "data.propertySnippet.title"
              },
              {
                "displayName": "Price",
                "attribute": "data.propertySnippet.price"
              },
              {
                "displayName": "Carpet Area",
                "attribute": "data.propertySnippet.area"
              },
              {
                "displayName": "Rating",
                "expression": "$abcd = $response[\'data\'][\'propertySnippet\'][\'area\'] * 0.1;return $abcd+999;"
              },
              {
                "displayName": "Area",
                "expression": "$response[\'data\'][\'propertySnippet\'][\'area\'] * 2000;"
              },
              {
                "displayName": "Built Up Area",
                "expression": "return data:propertySnippet:area"
              }
            ]'
            ,true);
    }

}
