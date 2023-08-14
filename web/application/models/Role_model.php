<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends Base_Model {	

	function __construct(){
		parent::__construct ();
		date_default_timezone_set("Asia/Taipei");
	}
    function writePremission($role_key, $routes){
		//select where contain role_key and drop role_key
		$sql = "SELECT * FROM route_table WHERE roles LIKE ? ";
		$parmeter = array("%{$role_key}%");
		$result = $this->db->query($sql, $parmeter)->result_array();

		foreach($result as $res){
			$sql = "UPDATE route_table SET roles =? WHERE id =?";
			$roles = str_replace("{$role_key},",'',$res['roles']) ;
			$parmeter = array ($roles, $res['id']);
			$this->db->query($sql,$parmeter);
		}

		// write in
		$routes_id = $this->travelTree($routes);
		foreach($routes_id as $id){
			$sql= "UPDATE route_table SET roles=concat(roles,?,',') WHERE id=?";
			$parmeter = array($role_key,$id);
			$this->db->query($sql,$parmeter);
		}

	}

    function buildTree(array &$elements, $parentId = 0) {
		$branch = array();
	
		foreach ($elements as $element) {
			if ($element['parent_id'] == $parentId) {
				$children = $this->buildTree($elements, $element['id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[$element['id']] = $element;
				unset($elements[$element['id']]);
			}
		}
		return $branch;
	}
	
	function createTree(&$list, $parent){
		$tree = array();
		foreach ($parent as $k=>$l){
			if(isset($list[$l['id']])){
				$l['children'] = $this->createTree($list, $list[$l['id']]);
			}
			$tree[] = $l;
		} 
		return $tree;
	}

	function formatIndex($arr){
		foreach ($arr as $key => $value){
			if (is_array($value)){
			$arr[$key] = $this->formatIndex($value);
			}
		}

		if (isset($arr['children'])){
			$arr['children'] = array_values($arr['children']);
		}
		return $arr;
	}

	function travelTree($routes,$res=[]){
		foreach($routes as $route){
			if(isset(($route['children']))){
				array_push($res, $route['id']);
				$res = $this->travelTree($route['children'],$res);
			}else{
				array_push($res, $route['id']);
			}
		}
		return $res;
	}
}