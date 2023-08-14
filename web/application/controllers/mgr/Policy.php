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
	
	public function updatePrivacyEn(){
		$input = $this->input->raw_input_stream;
		if($input){
			$sql = "UPDATE privacy SET content=? WHERE name='en' ";
			$parmeter = array($input);
			if($this->db->query($sql,$parmeter)){
				$this->output(TRUE,"success",array(
					'code' => 20000,
				));	
			}
		}
	}

	public function featchPrivacyEn(){
		$sql = "SELECT content FROM privacy WHERE name='en'";
		$content = $this->db->query($sql)->row_array();
		$this->output(TRUE,"success",array(
			'code' => 20000,
			'data' => $content
		));
	}

	public function updatePrivacy(){
		$input = $this->input->raw_input_stream;
		if($input){
			$sql = "UPDATE privacy SET content=? WHERE name='zh' ";
			$parmeter = array($input);
			if($this->db->query($sql,$parmeter)){
				$this->output(TRUE,"success",array(
					'code' => 20000,
				));	
			}
		}
	}

	public function featchPrivacy(){
		$sql = "SELECT content FROM privacy WHERE name='zh'";
		$content = $this->db->query($sql)->row_array();
		$this->output(TRUE,"success",array(
			'code' => 20000,
			'data' => $content
		));
	}

	public function updateTermsEn(){
		$input = $this->input->raw_input_stream;
		if($input){
			$sql = "UPDATE terms SET content=? WHERE name='en' ";
			$parmeter = array($input);
			if($this->db->query($sql,$parmeter)){
				$this->output(TRUE,"success",array(
					'code' => 20000,
				));	
			}
		}
	}

	public function featchTermsEn(){
		$sql = "SELECT content FROM terms WHERE name='en'";
		$content = $this->db->query($sql)->row_array();
		$this->output(TRUE,"success",array(
			'code' => 20000,
			'data' => $content
		));
	}

	public function updateTerms(){
		$input = $this->input->raw_input_stream;
		if($input){
			$sql = "UPDATE terms SET content=? WHERE name='zh' ";
			$parmeter = array($input);
			if($this->db->query($sql,$parmeter)){
				$this->output(TRUE,"success",array(
					'code' => 20000,
				));	
			}
		}
	}

	public function featchTerms(){
		$sql = "SELECT content FROM terms WHERE name='zh'";
		$content = $this->db->query($sql)->row_array();
		$this->output(TRUE,"success",array(
			'code' => 20000,
			'data' => $content
		));
	}
}

