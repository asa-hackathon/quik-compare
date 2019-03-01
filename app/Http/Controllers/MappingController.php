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

    public function showCreateMapping() {
        $category = DB::select("SELECT cat_id,category_name FROM `categories` where parent=1 and cat_id not in(1,93)");
        return view('create-mapping', ['vertical' => $category]);
    }
	public function mappingDashboard() {
		$select = DB::select("SELECT parent_category_name as Category, category_name as SubCat, map.api_endpoint, map. config FROM `mapping` as map inner join  categories as cat ON map.`sub_cat_id` = cat.`cat_id`");
		return view('mapping-dashboard', ['data' => $select]);
	}

    public function getCategory() {
        $category = DB::select("SELECT cat_id,category_name FROM `categories` where parent=1 and cat_id not in(1,93)");
        return $category;
    }

    public function getSubCategory() {
        $category_id = $_GET['category_id'];
        $category = DB::select("SELECT cat_id as subcat_id, category_name as subcat_name  FROM `categories` where parent != 1 AND parent_cat_id = $category_id");
        return $category;        
    }

    public function addMapping() {
    	$category = (int) $_POST['category'];
    	$sub_category = (int) $_POST['sub_category'];
    	$api_endpoint = $_POST['api_endpoint'];

    	$config = json_encode(['title' => 'doc.projectSnippet.name', 'price' => 'doc.projectSnippet.price']);
    	$query = "insert into mapping (category, sub_cat_id, api_endpoint, config) values ('$category', '$sub_category', '$api_endpoint', '$config')";
    	$addMapping = DB::insert($query);
    	var_dump($addMapping);
    }

    public function getConfig() {
        $vertical = array(
            "1" => 'Commonfloor',
            "2" => 'Quikr Homes',
            "3" => 'Quikr Cars',
            "4" => 'Quikr Services'
        );
 
        $category = array(
            'Residential' => [
                'sale',
                'rent'
            ],
            'Commercials' => [
                'sale',
                'rent'
            ]
        );

        $sub_category = array(
            'sale',
            'rent'
        );
    }
}
