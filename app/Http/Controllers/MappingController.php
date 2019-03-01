<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;

class MappingController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function addMapping() {
    	$vertical = $_POST['vertical'];
    	$sub_category = $_POST['sub_category'];
    	$end_point = $_POST['end_point'];
    	$query = "insert into mapping (category, sub_cat_id, config) values ('vertical', 'sub_category', 'end_point')";
    	$addMapping = DB::insert(DB::raw($query));
    	var_dump($addMapping);

    }
}
