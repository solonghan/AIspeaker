<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class System extends Base_Controller{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->load->model('Jwt_model');
	}

    public function login(){
		$input = json_decode($this->input->raw_input_stream, true);
		if($input){
			$account = $input['username'];
			$pwd = md5($input['password']);
			$login = $this->Dashboard_model->login($account,$pwd);
			
			if($login['status']){
				$this->output(TRUE,"success",array(
					'code' => 20000,
					'data' => $login['data'],
				));
			}else{
				$this->output(TRUE,"success",array(
					'code' => 60204,
					'message' => 'Account and password are incorrect.'
				));
			}
		}
	}

	public function getUserInfo(){
		$token = $this->input->get('token', TRUE);
		if(!empty($token) && $_SERVER['REQUEST_METHOD'] != 'OPTIONS'){
			$user = $this->Jwt_model->verify_token($token);
			$user_id = $user['id'];
			$sql = "SELECT id,avatar,user_name, user_group.role_name , user_group.is_delete FROM users
					LEFT JOIN user_group on users.user_group = user_group.role_key
					WHERE id =?";
			$parmeter =array($user_id);
			$result = $this->db->query($sql, $parmeter)->row_array();
			$introduction= 'I am a super administrator';
			$avatar= $result['avatar'];
			$name= $result['user_name'];
			$roles= [$result['role_name']];
			
			if($result['is_delete']==1){
				$this->output(TRUE,"success",array(
					'code' => 500,
					'message' => '權限組無效.'
				));
				return;
			}
			$data = array(
				'id'	=> $result['id'],
				'introduction' => $introduction,
				'avatar'	=> $avatar,
				'name'	=> $name,
				'roles'	=> $roles
			);
			$this->output(TRUE,"success",array(
				'code' => 20000,
				'data' => $data
			));
		}
	}

    public function adduser(){
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input){
			$user_name = $input['user_name'];
			$account = $input['account'];
			$password = md5($input['password']);
			$user_group = $input['user_group'];

			// select if exist
			$sql = "SELECT count(*) as count FROM users WHERE account=? AND status='on'";
			$parmeter = array($account);
			$exist = $this->db->query($sql,$parmeter)->row_array()['count'];
			if($exist >0){
				$this->output(TRUE, "fail.", array(
					'code'        =>    500,
					'message'    =>  '帳號已經存在'
				));
				return;
			}

			if(empty($user_group)){
				$this->output(TRUE, "fail.", array(
					'code'		=>	500,
					'message'	=>  'user_group不能為空.'
				));
				return;
			}
			// add
			$sql = "INSERT INTO users (user_name, account, password, user_group)
					VALUES (?, ?, ?, ?)";
			$parmeter = array($user_name, $account, $password, $user_group);
			if($this->db->query($sql, $parmeter)){
				$id = $this->db->insert_id();
				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
					'data'		=>	$id,
				));
			}
		}
	}

    public function updateUser(){
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input){
			$user_id = $input['user_id'];
			$user_name = $input['user_name'];
			$account = $input['account'];
			$password = $input['password'];
			$user_group = $input['user_group'];

			$sql = "SELECT count(*) as count FROM users WHERE account=? AND status='on' AND id<>?";
			$parmeter = array($account,$user_id);
			$exist = $this->db->query($sql,$parmeter)->row_array()['count'];
			if($exist >0){
				$this->output(TRUE, "fail.", array(
					'code'        =>    500,
					'message'    =>  '帳號已經存在'
				));
				return;
			}

			if(empty($password)){
				$sql = "UPDATE users SET user_name=?, account=?, user_group=? WHERE id=?";
				$parmeter = array($user_name, $account, $user_group,$user_id);
			}else{
				$password = md5($password);
				$sql = "UPDATE users SET user_name=?, account=?, password=?, user_group=? WHERE id=?";
				$parmeter = array($user_name, $account, $password, $user_group,$user_id);
			}

			if($this->db->query($sql, $parmeter)){
				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
				));
			}
		}
	}
	public function editProfile(){
		$input = json_decode($this->input->raw_input_stream, true);
		if($input){
			$user_id = $input['user']['id'];
			$user_name = $input['user']['name'];
			$avatar = $input['user']['avatar'];
			$password = $input['pwd'];
			$chkpwd = $input['chkpwd'];
			// check if pwd is set 
			if(!empty($password) || !empty($chkpwd)){
				if($password != $chkpwd){
					$this->output(TRUE, "success.", array(
						'code'		=>	500,
						'message'	=> '兩次密碼不相同',
					));
				}
			}
			
			if(empty($password)){
				$sql = "UPDATE users SET user_name=?,avatar=?  WHERE id=?";
				$parmeter = array($user_name, $avatar,$user_id);
			}else{
				$sql = "UPDATE users SET user_name=? ,avatar=?,password=? WHERE id=?";
				$parmeter = array($user_name, $avatar,md5($password),$user_id);
			}
			
			if($this->db->query($sql, $parmeter)){
				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
				));
			}
		}
	}
    public function deleteUser(){
		$input = $this->input->raw_input_stream;
		if ($input){
			$user_id = $input;

			$sql = "UPDATE users SET status='off' WHERE id=?";
			$parmeter = array($user_id);
			if($this->db->query($sql, $parmeter)){
				$this->output(TRUE, "success.", array(
					'code'		=>	20000,
				));
			}
		}
	}

    public function getUserList(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$page = $input['page'];
			$limit = $input['limit'];
			$query = $input['query'];
			$start = $page==1 ? $page = 0 : ($page-1) * $limit;

			// craft where
			if($query){
				$where = " WHERE status = 'on' AND user_name LIKE '%{$query}%' OR account LIKE '%{$query}%'";
			}else{
				$where = " WHERE status = 'on'";
			}

			// count
			$sql = "SELECT count(*) as count FROM users {$where}";
			$count = $this->db->query($sql)->row_array()['count'];
			$data['total'] = $count;
			// fetch data
			$sql = "SELECT u.id as user_id, u.* ,ug.role_name as user_group
					FROM users as u 
					LEFT JOIN user_group as ug ON u.user_group = ug.role_key
					{$where}
					LIMIT ?,?";
			$parmeter = array($start,$limit);
			$data['items'] = $this->db->query($sql,$parmeter)->result_array();
	
			$this->output(TRUE, "success.", array(
				'code'		=>	20000,
				'data'	=> $data
			));
		}
		
	}

    public function getUserGroupOption(){
		$sql = "SELECT role_key, role_name FROM user_group WHERE is_delete=0";
		$user_groups = $this->db->query($sql)->result_array();
		$data['items'] = $user_groups;
		$this->output(TRUE, "success.", array(
			'code'		=>	20000,
			'data'	=> $data
		));
	}
	public function loginRecord(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$page = $input['page'];
			$limit = $input['limit'];
			$start = $page==1 ? $page = 0 : ($page-1) * $limit;
			$sql = "SELECT count(*) as count FROM login_log";
			$count = $this->db->query($sql)->row_array()['count'];
			$data['total'] = $count;

			$sql = "SELECT * FROM login_log LIMIT ?,?";
			$parmeter = array($start,$limit);
			$record =  $this->db->query($sql,$parmeter)->result_array();
			$data['items'] = $record;
			$this->output(TRUE, "success.", array(
				'code'		=>	20000,
				'data'	=> $data
			));
		}
	}

	public function uploadimg(){
		$config['upload_path']="uploads/";
        $config['allowed_types']='gif|jpg|png';
        $this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file')){
			$error = array('error' => $this->upload->display_errors());
			var_dump($error);
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			
			$filename = $data['upload_data']['file_name'];
			$url = $config['upload_path'].$filename;

			$res['name'] = $filename;
			$res['url'] = $url;
			echo json_encode($res);
		}
	}
	
}
?>