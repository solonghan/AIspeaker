<?php

use function PHPSTORM_META\map;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Role extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Role_model');
	}
	public function getRoles(){
		$sql = "SELECT * FROM user_group WHERE is_delete <> 1";
		if($result = $this->db->query($sql)){
			$data = $result->result_array();
			foreach($data as $key => $val){
				$data[$key]['routes'] = json_decode($data[$key]['routes']);
			}
			$this->output(TRUE, "success.", array(
				'code'		=>	20000,
				'data'		=>	$data,
			));
		}else{
			$this->output(FALSE, "fail.");
		}
	}

	public function updateRoles(){
		$input = json_decode($this->input->raw_input_stream, true);
		if(!empty($input)){
			$description = $input['description'];
			$role_key = $input['role_key'];
			$role_name = $input['role_name'];
			$routes = $input['routes'];

			// wrtie real premission
			$this->Role_model->writePremission($role_key, $routes);

			// write profile
			$route = json_encode($routes);
			$sql = "UPDATE user_group SET role_name=?, description=? , routes=? WHERE role_key=?";
			$parmeter = array($role_name, $description, $route, $role_key);
			$this->db->query($sql,$parmeter);

			$this->output(TRUE, "success.", array(
				'code'		=>	20000,
			));
			
		}
	}



	public function deleteRoles(){
		$role_key = json_decode($this->input->raw_input_stream, true);
		if(!empty($role_key)){
			$sql = "UPDATE user_group SET is_delete = 1 WHERE role_key=?";
			$parmeter = array($role_key);
			if($this->db->query($sql,$parmeter)){
				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
				));
			}else{
				$this->output(FALSE, "fail.");
			}
		}
	}
	
	public function addRoles(){
		$input = json_decode($this->input->raw_input_stream, true);
		if(!empty($input)){			
			$description = $input['description'];
			$routes = json_encode($input['routes']);
			$role_name = $input['role_name'];

			// if exist 
			$sql = "SELECT * FROM user_group WHERE role_name=?";
			$parmeter = array($role_name);
			$res = $this->db->query($sql,$parmeter)->row_array();
			if($res && $res['is_delete']==1){
				// exist but delete

				// restore
				$sql = "UPDATE user_group SET is_delete=?, description=?, routes=? WHERE role_key = ?";
				$parmeter = array($is_delete=0,$description, $routes, $res['role_key']);
				$this->db->query($sql,$parmeter);
				// writle real permission
				$this->Role_model->writePremission($res['role_key'], $input['routes']);

				$data['role_key'] = $res['role_key'];
				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
					'data'		=>	$data,
				));
				return;
			}else if($res && $res['is_delete']==0){
				// exist and still online
				$this->output(TRUE, "success.", array(
					'code'		=>	500,
					'message'		=>	'群組名稱重複.',
				));
				return;
			}

			// if not exist
			$sql = "INSERT INTO user_group (role_name, description, routes) VALUES(?,?,?)";
			$parmeter = array($role_name, $description, $routes);
			if($this->db->query($sql,$parmeter)){
				$data['role_key'] = $this->db->insert_id();

				//write real Permission
				$this->Role_model->writePremission($data['role_key'], $input['routes']);

				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
					'data'		=>	$data,
				));
			}else{
				$this->output(FALSE, "fail.");
			}
		}
		
	}


	public function getRoutes(){
		// roles data
		$sql = "SELECT * FROM user_group";
		$roles = $this->db->query($sql)->result_array();
		// roles map
		foreach($roles as $role){
			$role_map[$role['role_key']] = "'{$role['role_name']}'";
		}

		$sql = "SELECT * FROM route_table ORDER BY `sequence`=0,`sequence` asc,parent_id asc";
		$routes = $this->db->query($sql)->result_array();
		foreach($routes as &$route){
			// replace role_id to role_name
			$roles = explode(',' ,$route['roles']);
			foreach($roles as &$role){
				if(!empty($role)){
					$permission = $role_map[$role].',';
				}
			}
			if(isset($permission)){
				$permission = rtrim($permission, ", ");
				$permission = "[{$permission}]";
			}else{
				$permission='';
			}
			// pack title,icon,roles into meta
			$route['meta'] = array(
				'title'	=> $route['title'],
				'icon'	=> $route['icon'],
				// 'roles'	=> $route['roles'],
				'roles'	=> $permission,
			);

			// drop unnecessary value
			unset($route['title']);
			unset($route['icon']);
			unset($route['roles']);
			if(empty($route['alwaysShow'])){
				unset($route['alwaysShow']);
			}
			if(empty($route['redirect'])){
				unset($route['redirect']);
			}
		}
		// buildTree
		$new = array();
		foreach ($routes as $a){
			$new[$a['parent_id']][] = $a;
		}
		$routes = $this->Role_model->createTree($new, $new[0]);

		// format make index like [0],[1],[2] instead of ['1'],['5'],['6']
		$routes = array_values($routes);
		$routes = $this->Role_model->formatIndex($routes);
		
		$notfound = array(
			'path' => '*',
			'redirect' => '/404',
			'hidden' => 'true'
		);
		array_push($routes,$notfound);
		$this->output(TRUE, "success.", array(
			'code'		=>	20000,
			'data'		=>	$routes,
		));
		

	}
	public function getBtnRole(){
		$input = json_decode($this->input->raw_input_stream, true);
		if($input){
			$role_name = $input['role_name'];
			$sql = "SELECT * FROM user_group WHERE role_name = ?";
			$param = array($role_name);
			$data = $this->db->query($sql, $param)->row_array()['btn'];
			$data =explode(',',$data);
			$this->output(true,"success", array(
				'code' => 20000,
				'data' => $data,
			));
		}
	}
	public function updateBtnRole(){
		$input = json_decode($this->input->raw_input_stream, true);
		if($input){
			$role_key = $input['role_key'];
			$btn_priv = $input['priv'];
			$btn = "";
			if($btn_priv['pointAdd']){
				$btn.="pointAdd,";
			}
			if($btn_priv['pointRecord']){
				$btn.="pointRecord,";
			}
			if($btn_priv['sentence']){
				$btn.="sentence,";
			}
			
			$sql = "UPDATE user_group SET btn = ? WHERE role_key = ?";
			$param = array($btn, $role_key);
			$this->db->query($sql, $param);
			$this->output(true,'success', array(
				'code' => 20000,
			));
		}
	}

}

