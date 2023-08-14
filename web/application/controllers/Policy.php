<?php

use function PHPSTORM_META\map;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Policy extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
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
	public function subscription_terms_of_service_zh(){
		$sql = "SELECT content FROM terms WHERE name='zh'";
		$content = $this->db->query($sql)->row_array();
		$this->load->view('policy',$content);
	}
	public function subscription_terms_of_service_en(){
		$sql = "SELECT content FROM terms WHERE name='en'";
		$content = $this->db->query($sql)->row_array();
		$this->load->view('policy',$content);
	}
	public function privacy_policy_zh(){
		$sql = "SELECT content FROM privacy WHERE name='zh'";
		$content = $this->db->query($sql)->row_array();
		$this->load->view('policy',$content);
	}
	public function privacy_policy_en(){
		$sql = "SELECT content FROM privacy WHERE name='en'";
		$content = $this->db->query($sql)->row_array();
		$this->load->view('policy',$content);
	}
}

