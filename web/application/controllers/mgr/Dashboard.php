<?php

use function PHPSTORM_META\map;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, X-token");
header('Access-Control-Allow-Credentials: true');

class DashBoard extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
	}
	public function test()
	{
		$par = $this->input->post("par");
		if($par==1){
			$this->output(TRUE, "取得成功", array(
				'msg'		=>	'hello world',
				'data'		=>	'$data',
			));
		}else{
			$this->output(FALSE, "Filure");
		}

	}

	public function login(){
		$input = json_decode($this->input->raw_input_stream, true);
		$account = $input['username'];
		$pwd = md5($input['password']);
		$login = $this->Dashboard_model->login($account,$pwd);

		if($login['status']){
			$this->output(TRUE,"success",array(
				'code' => 20000,
				'data' => $login['data'],
			));
		}else{
			$this->output(False,'fail');
		}
	}

	public function getInfo(){
		$token = $this->input->post("token");
		roles: ['admin'];
    	introduction: 'I am a super administrator';
    	avatar: 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
    	name: 'Super Admin';
		$data['info'] = 'info123';
		$this->output(TRUE,"success",array(
			'code' => 20000,
			'data' => $data
		));	
	}
}
