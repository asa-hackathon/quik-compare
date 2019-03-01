<?php

namespace App\Http\Controllers;

use App\Mapping;
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
                    "image" => "i5/20181003/2-BHK-1468-Sq-ft-Apartment-for-Sale-in-Yelahanaka--Bangalore-VB201705171774173-ak_LWBP1131831532-1538567833nr424x318sm124x93sq88x66lg728x546gv262x175.jpeg",
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
                    "image" => "i5/20181003/2-BHK-1468-Sq-ft-Apartment-for-Sale-in-Yelahanaka--Bangalore-VB201705171774173-ak_LWBP1131831532-1538567833nr424x318sm124x93sq88x66lg728x546gv262x175.jpeg",
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
                    "image" => "i5/20181003/2-BHK-1468-Sq-ft-Apartment-for-Sale-in-Yelahanaka--Bangalore-VB201705171774173-ak_LWBP1131831532-1538567833nr424x318sm124x93sq88x66lg728x546gv262x175.jpeg",
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
        if (empty($csvIds) || empty($subCatId)) {
            return [];
        }

        return [];
    }

}