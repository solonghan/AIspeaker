<?php

use function PHPSTORM_META\map;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class News extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function delNews(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$id = $input['id'] ?? "";
			$sql = "UPDATE news SET is_delete = 1 WHERE id=?";
			$param = array($id);
			$res = $this->db->query($sql, $param);
			
			$this->output(true,'success',array(
				'code' => 20000,
			));
		}
	}
	
	public function createNews()
	{
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$title = $input['title'] ?? "";
			$content = $input['content'] ?? "";
			$isEdit = $input['isEdit'] ?? "";
			$lang = $input['lang'] ?? 'tc';
			$id = $input['id'] ?? "";
			
			if ($isEdit == true && !empty($id)) {
				// update
				$sql = "UPDATE news SET title=?, content=?, lang=?
						WHERE id = ?";
				$param = array($title, $content, $lang, $id);
				$res = $this->db->query($sql, $param);
			} else {
				//create
				$sql = "INSERT INTO news (title, content, lang)
					VALUES(?, ?, ?)";
				$param = array($title, $content, $lang);
				$res = $this->db->query($sql, $param);
			}

			
			$this->output(true, 'success', array(
				'code' => 20000,
			));
		}
	}

	public function getNewsList()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input) {
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$page = $this->pagging($page, $limit);
			$lang = $input['lang'] ?? 'tc';
			$query = $input['query'] ?? "";
			
			// handle filter
			$where = " WHERE is_delete = 0 AND lang = '{$lang}'";
			if ($query) {
				$where .= " AND (title LIKE '%{$query}%'  OR content LIKE '%{$query}%' OR id LIKE '%{$query}%') ";
			}

			$count_sql = "SELECT * FROM news
						  {$where}
						  ORDER BY id DESC";
			$data['total'] = $this->db->query($count_sql)->num_rows();
			
			//data sql
			$data_sql = $count_sql." LIMIT ?, ?";
			$param= array($page['start'], $page['end']);
			$data['data'] = $this->db->query($data_sql,$param)->result_array();
			$this->output(true, 'success', array(
				'code' => 20000,
				'data' => $data,
			));
		}
	}

}

