<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aispeaker_model extends Base_Model {	

	private $api_url = "https://www.apreventmed.com:2096/messenger/";

	function __construct(){
		date_default_timezone_set("Asia/Taipei");
	}
	
	public function send_mail(array $data){
		$param = array(
			'model' => 'Contactus',
			'command' => 'Reply',
			'language' => $data['language'],
			'name' => $data['name'],
			'email' => $data['email'],
			'contact_id' => $data['contact_id'],
		);
		$result = $this->curl_post('contact/send_contactus_mail.php',$param);
		$result = json_decode($result,true);
		if($result['status'] == 1){
			return true;
		}

		return false;
	}

	private function curl_post($url, $post){
		$url = $this->api_url . $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	

}