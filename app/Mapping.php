<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;
use Log;

class Mapping extends Model
{
    protected $table = "mapping";

    public static function transformAttributes($subCatId, $response) {
        try {
            $returnData = [];
            $configData = Mapping::where(['sub_cat_id' => $subCatId])
                                ->select(['config'])
                                ->first();
            if (is_null($configData)) {
                return [];
            }

            $jsonConfigData = json_decode($configData->config, true);

            foreach ($jsonConfigData as $value) {
                if (isset($value['attribute'])) {
                    $returnData[$value['displayName']] = Helper::parseAttribute($response, $value['attribute']);
                } elseif (isset($value['expression'])) {
                    $returnData[$value['displayName']] = Helper::evaluateExpression($response, $value['expression']);
                }
            }

            return $returnData;
        } catch (\Throwable $e) {
            Log::error('Error occurred in ' . __METHOD__ . ' : ' . $e->getMessage());
            return [];
        }
    }

}
