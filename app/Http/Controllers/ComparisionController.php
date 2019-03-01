<?php

namespace App\Http\Controllers;

use App\Mapping;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ComparisionController extends Controller
{

    public function compare(Request $request)
    {
        $csvIds = $request->get('ids');
        $subCatId = $request->get('subcat');

        // Base case
        if (!isset($csvIds) || empty($csvIds) || !isset($subCatId) || empty($subCatId)) {
            return [];
        }

        return response()->json([
            "fields" => [
                "Price",
                "Area (in Sqft)",
                "Address",
                "Available From",
                "Furnished",
                "Registration Charges",
                "Carpet Area"
            ],
            "products" => [
                [
                    "id" => 1,
                    "title" => "Sobha City",
                    "image" => "/r1/20170731/ak_1200_1396257863-1501504007_300x300.png",
                    "fields" => [
                        "Price" => 1200000,
                        "Area (in Sqft)" => 1300,
                        "Address" => "Thanisandra, Bangalore",
                        "Available From" => "10 March 2019",
                        "Furnished" => true,
                        "Registration Charges" => 150000,
                        "Carpet Area" => 1200
                    ]
                ],
                [
                    "id" => 2,
                    "title" => "RES Residency",
                    "image" => "r1/20170731/ak_1200_1324575520-1501504002_300x300.jpeg",
                    "fields" => [
                        "Price" => 1200000,
                        "Area (in Sqft)" => 1300,
                        "Address" => "Thanisandra, Bangalore",
                        "Available From" => "10 March 2019",
                        "Furnished" => true,
                        "Registration Charges" => 150000,
                        "Carpet Area" => 1200
                    ]
                ],
                [
                    "id" => 3,
                    "title" => "Manyata Residency",
                    "image" => "r1/20170731/ak_1200_1422121845-1501504007_300x300.jpeg",
                    "fields" => [
                        "Price" => 19000000,
                        "Area (in Sqft)" => 1300,
                        "Address" => "Nagawara, Bangalore",
                        "Available From" => "18 March 2019",
                        "Furnished" => true,
                        "Registration Charges" => 150000,
                        "Carpet Area" => 1500
                    ]
                ]
            ]
        ]);
    }


    public function doCompare(Request $request) {
        $csvIds = $request->get('ids', null);
        $subCatId = $request->get('subcat', null);

        // Base case
        if (empty($csvIds) || empty($subCatId)) return [];

        $mapping = Mapping::where(['sub_cat_id' => $subCatId])
                    ->first();

        if (empty($mapping)) return [];

        $mapping = $mapping->toArray();

        // Configuration
        $url = $mapping['api_endpoint'];
        $config = $mapping['config'];

        dd(json_decode(Mapping::find(5)->config, true));

        // Curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_NOBODY, TRUE); // remove body

        return $mapping['config'];
    }

}