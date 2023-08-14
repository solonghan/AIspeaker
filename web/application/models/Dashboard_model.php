<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends Base_Model {	

	function __construct(){
		parent::__construct ();
		date_default_timezone_set("Asia/Taipei");
		$this->load->model('Jwt_model');
	}

	function output($status='',$msg='',$data=''){
		$result = array (
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);

		return $result;
	}

	function login ($account,$md5){
		$sql = "SELECT users.*,role_name FROM users 
				LEFT JOIN user_group on users.user_group = user_group.role_key
				WHERE account=? AND password=? AND status='on' ";
		$parmeter = array($account,$md5);
		$result = $this->db->query($sql,$parmeter)->row_array();
		if(!empty($result['id'])){
			$data['id'] = $result['id'];
			$token=$this->Jwt_model->generate_token($data);

			unset($data);
			$data['token'] = $token;
			$this->logingLog($result);
			return $this->output(True,'success',$data);
		}else{
			return $this->output(False,'fail');
		}
	}

	function logingLog($user){
		$sql = "INSERT INTO login_log (user_id, username, account ,usergroup)
				VALUES (?,?,?,?)";
		$parmeter = array($user['id'], $user['user_name'], $user['account'],$user['role_name']);
		$this->db->query($sql,$parmeter);
	}
	function getUserInfo($user_id){
		$sql = "SELECT  FROM member WHERE id=?";
		$parmeter = array($user_id);
		$result = $this->db->query($sql,$parmeter);
		
	}

}