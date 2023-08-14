<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');
class Member extends Base_Controller {
	private $country = array(
		'' => '',
		'af'	=>	'南非荷蘭語',
		'mk'	=>	'馬其頓語',
		'ar'	=>	'阿拉伯語',
		'ms'	=>	'馬來西亞語',
		'be' => '貝勞語',
		'mt' => '馬耳他語',
		'bg'	=>'保加利亞語',
		'nl'	=>'荷蘭語',
		'ca'	=>'加泰羅尼亞語',
		'no'	=>'挪威語',
		'cs'	=>'捷克語',
		'pl'	=>'波蘭語',
		'da'	=>'丹麥語',
		'pt'	=>'葡萄牙語',
		'de'	=>'德語',
		'rm'	=>'拉丁語系',
		'el'	=>'希臘語',
		'ro'	=>'羅馬尼亞語',
		'en'	=>'英語',
		'ru'	=>'俄語',
		'es'	=>'西班牙語',
		'sb'	=>'索布語',
		'et'	=>'愛沙尼亞語',
		'sk'	=>'斯洛伐克語',
		'eu'	=>'巴斯克語',
		'sl'	=>'斯洛文尼亞語',
		'fa'	=>'波斯語',
		'sq'	=>'阿爾巴尼亞語',
		'fi'	=>'芬蘭語',
		'sr'	=>'塞爾維亞語',
		'fo'	=>'法羅語',
		'sv'	=>'瑞典語',
		'fr'	=>'法語',
		'sx'	=>'蘇圖語',
		'gd'	=>'蓋爾語',
		'sz'	=>'薩摩斯語',
		'he'	=>'希伯來語',
		'th'	=>'泰語',
		'hi'	=>'北印度語',
		'tn'	=>'瓦納語',
		'hr'	=>'克羅地亞語',
		'tr'	=>'土耳其語',
		'hu'	=>'匈牙利語',
		'ts'	=>'湯加語',
		'in'	=>'印度尼西亞語',
		'uk'	=>'烏克蘭語',
		'is'	=>'冰島語',
		'ur'	=>'烏爾都語',
		'it'	=>'意大利語',
		've'	=>'文達語',
		'ja'	=>'日語',
		'vi'	=>'越南語',
		'ji'	=>'依地語',
		'xh'	=>'科薩語',
		'ko'	=>'韓文',
		'zh'	=>'簡體中文（中國）',
		'lt'	=>'立陶宛語',
		'zh-tw'	=>'繁體中文（台灣）'
	);

	private $SYMPTOMS = array('中風', '腦性麻痺', '帕金森氏症', '多發性硬化', '肌萎縮性脊髓側索硬化症', '頭頸癌', '其他');

	function getMemberList(){
		$input = json_decode($this->input->raw_input_stream, true);
		if($input){
			$page = $input['page'];
			$limit = $input['limit'];
			$query = $input['query'];
			$start = $page==1 ? $page = 0 : ($page-1) * $limit;

			// craft where
			if($query){
				$where = " WHERE NAME LIKE '%{$query}%' OR USER LIKE '%{$query}%'";
			}else{
				$where = " ";
			}

			// count
			$sql = "SELECT count(*) as count FROM org_user_main {$where}";
			$count = $this->db->query($sql)->row_array()['count'];
			$data['total'] = $count;

			$sql = "SELECT * FROM org_user_main 
					{$where}
					LIMIT {$start},{$limit}";
			$data['data'] = $this->db->query($sql)->result_array();

			//foramt lang
			foreach($data['data'] as &$item){
				if($item){
					@$item['COUNTRY'] = $this->country[$item['COUNTRY']];
				}
			}
			$this->output(TRUE, "success.", array(
				'code'		=>	20000,
				'data'	=> $data
			));
		}
	}

	function getMemberInfo(){
		$input = json_decode($this->input->raw_input_stream, true);
		if($input){
			$account = $input['account'];
			$sql = "SELECT * FROM org_user_main WHERE USER = ?";
			$param = array($account);
			$data['data'] = $this->db->query($sql, $param)->row_array();
			
			//format 
			@$data['data']['COUNTRY'] = $this->country[$data['data']['COUNTRY']];
			@$data['data']['SYMPTOMS'] = $this->SYMPTOMS[$data['data']['SYMPTOMS']];
			$data['data']['ADDRESS'] ? empty($data['data']['ADDRESS']) : $data['data']['ADDRESS'] = '尚未填寫';
			$this->output(TRUE, "success.", array(
				'code'		=>	20000,
				'data'	=> $data
			));
		}
	}
}
