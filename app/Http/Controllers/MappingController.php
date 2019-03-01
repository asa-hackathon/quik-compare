<?php

namespace App\Http\Controllers;

use App\Mapping;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DB;

const API_ENDPOINT_ID_PLACEHOLDER = '*****';

class MappingController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $this->returnData = array();
    }

    public function showCreateMapping() {
        $category = $this->getCategory();
        return view('create-mapping', ['vertical' => $category]);
    }
	public function mappingDashboard() {
		$select = DB::select("SELECT parent_category_name as Category, category_name as SubCat, map.api_endpoint, map. config, map.id FROM `mapping` as map inner join  categories as cat ON map.`sub_cat_id` = cat.`cat_id`");
		return view('mapping-dashboard', ['data' => $select]);
	}

    public function getMappingData() {
        $id = $_GET['id'];
        $select = DB::select("SELECT parent_category_name as CategoryName, category_name as SubCatName, map.api_endpoint, map. config, map.id, map.category as category_id, map.sub_cat_id as sub_category_id, map.test_id, map.api_headers FROM `mapping` as map inner join  categories as cat ON map.`sub_cat_id` = cat.`cat_id` where id=$id");
        return $select;
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
        $config = "";
    	$category = isset($_POST['category']) ? (int) $_POST['category'] : '';
    	$sub_category = isset($_POST['sub_category']) ? (int) $_POST['sub_category'] : '';
    	$api_endpoint = isset($_POST['api_endpoint']) ? $_POST['api_endpoint'] : '';
        $api_headers = isset($_POST['api_headers']) ? $_POST['api_headers'] : '';
        $test_id = isset($_POST['test_id']) ? $_POST['test_id'] : '';

        if(isset($_POST['configValue']) && isset($_POST['configValue'])) {
            $configMain = array();
            for($i=0 ; $i < count($_POST['configValue']); $i++) {
                $configMain[] = array("displayName" => $_POST['configLabel'][$i], "attribute" => $_POST['configValue'][$i]);
            }
            $config = json_encode($configMain);                    
        }

        if(!empty($_POST['mapping_old_id'])) {
            $id = $_POST['mapping_old_id'];
            $query = "update mapping set category = '$category', sub_cat_id = '$sub_category', api_endpoint = '$api_endpoint', test_id = '$test_id', api_headers = '$api_headers', config = '$config' where id = $id";
        } else {
            $query = "insert into mapping (category, sub_cat_id, api_endpoint, test_id, api_headers, config) values ('$category', '$sub_category', '$api_endpoint', '$test_id', '$api_headers', '$config')";
        }
    	$addMapping = DB::insert($query);
        $category = $this->getCategory();
        return redirect()->route('mapping-dashboard');
    }

    public function getApiDropDownParams(Request $request) {
        $subCatId = $request->get('subCatId');
        $configuration = Mapping::where(['sub_cat_id' => $subCatId])->first();

        if (empty($configuration)) {
            return [];
        }

        $configuration = $configuration->toArray();
        $apiEndpoint = $configuration['api_endpoint'];
        $test_id = $configuration['test_id'];

        // Return if the API end point doesn't have a id placeholder
        if (!str_contains($apiEndpoint, API_ENDPOINT_ID_PLACEHOLDER) || empty($test_id)) {
            return [];
        }

        $headers = empty($configuration['api_headers']) ? [] : json_decode($configuration['api_headers'], true);

        if (empty($headers)) {
            $headers = [];
        }

        $curlHeaders = [];
        $testEndpoint = str_replace(API_ENDPOINT_ID_PLACEHOLDER, $test_id, $apiEndpoint);

        foreach ($headers as $key => $value) {
            $curlHeaders[] = $key . ': ' . $value;
        }

        self::getKeyDropDowns($testEndpoint, $headers);
        return $this->returnData;
    }

    public function getKeyDropDowns($apiEndpointUrl, $headers){
        $headerData = array();
        $curl_obj = curl_init();
        $headerData[] = "Content-Type:application/json";
        foreach($headers as $key=>$value){
            $headerData[] =  $key.":".$value;
        }
        curl_setopt($curl_obj, CURLOPT_URL, $apiEndpointUrl);
        curl_setopt($curl_obj, CURLOPT_HTTPHEADER, $headerData);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
        $jsonData = curl_exec($curl_obj);
        $data = (array)json_decode($jsonData);
        $key = '';
        self::foreachValueCompution($key,$data);
    }

    public function foreachValueCompution($pushData,$data){
        $tempPushData = '';
        foreach($data as $key=>$value){
            if(stripos($key,"status") !== FALSE || stripos($key,"message") !== FALSE)
                continue;
            if($key===0){
                $this->returnData[] = $pushData;
                break;
            }
            if((isset($pushData) && $pushData!=''))
                $tempPushData = $pushData .".". $key;
            else
                $tempPushData = $key;
            if(is_object($value)){
                $value = (array)$value;
            }
            if(is_array($value)){
                self::foreachValueCompution($tempPushData,$value);
            }else{
                $this->returnData[] = $tempPushData;
            }
        }
    }


}
