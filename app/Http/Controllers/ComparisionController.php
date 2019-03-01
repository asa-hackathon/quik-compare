<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComparisionController extends Controller
{

    public function compare(Request $request)
    {
        $csvIds = $request->get('ids');

        if (!isset($csvIds) || empty($csvIds)) {
            return [];
        }

        $ids = explode(',', $csvIds);

        return $ids;
    }

}