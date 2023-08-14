<?php

use function PHPSTORM_META\map;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Faq extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function delQa(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$id = $input['id'] ?? "";
			$sql = "UPDATE faq_content SET is_delete = 1 WHERE id=?";
			$param = array($id);
			$res = $this->db->query($sql, $param);
			
			$this->output(true,'success',array(
				'code' => 20000,
			));
		}
	}
	
	public function createQA()
	{
		$input = json_decode($this->input->raw_input_stream,true);
		if ($input) {
			$question = $input['question'] ?? "";
			$answer = $input['answer'] ?? "";
			$topic_id = $input['topic_id'] ?? "";
			$lang = $input['lang'] ?? 'tc';
			$isEdit = $input['isEdit'] ?? "";
			$id = $input['id'] ?? "";
			if(empty($topic_id)){
				throw new RuntimeException('param not valid');
			}


			if($isEdit == true && !empty($id)){
				// update
				$sql = "UPDATE faq_content SET topic_id=?, question=?, answer=?, lang=?
						WHERE id = ?";
				$param = array($topic_id, $question, $answer, $lang, $id);
				$res = $this->db->query($sql, $param);
			}else{
				//create
				$sql = "INSERT INTO faq_content (topic_id, question, answer, lang)
					VALUES(?, ?, ?, ?)";
				$param = array($topic_id, $question, $answer, $lang);
				$res = $this->db->query($sql, $param);
			}

			
			$this->output(true,'success',array(
				'code' => 20000,
			));
		}
	}
	public function createTopic()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input) {
			$icon = $input['icon'] ?? "" ;
			$lang = $input['lang'] ?? 'tc';
			$title = $input['title'] ?? "" ;
			$data = array('icon' => $icon, 'title' => $title, 'lang' => $lang);
			$this->db->insert('faq_topic', $data);
			$this->output(true, 'success', array(
				'code' => 20000,
			));
		}
	}

	public function editTopic(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$id = $input['id'] ?? "" ;
			$icon = $input['icon'] ?? "" ;
			$title = $input['title'] ?? "" ;

			$sql = "UPDATE faq_topic SET icon = ? , title =? WHERE id = ?";
			$param = array($icon, $title, $id);
			$this->db->query($sql, $param);
			$this->output(true,'success',array(
				'code' => 20000,
			));

		}
	}

	public function delTopic(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$id = $input['id'] ?? "" ;
			$sql = "UPDATE faq_topic SET is_delete = 1 WHERE id = ?";
			$param = array($id);
			$this->db->query($sql, $param);
			$this->output(true,'success',array(
				'code' => 20000,
			));
		}
	}

	public function getTopicOption()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if (!$input) {
			return;
		}
		$lang = $input['lang'] ?? "tc";

		$sql = "SELECT * FROM faq_topic WHERE is_delete = 0 AND lang = ?";
		$param = [$lang];
		$data['data'] = $this->db->query($sql, $param)->result_array();
		$this->output(true, 'success', array(
			'code' => 20000,
			'data' => $data,
		));
	}

	public function getTopicList()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input) {
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$lang = $input['lang'] ?? 'tc';
			$page = $this->pagging($page, $limit);

			$query = $input['query'] ?? "";
			
			// craft where
			$where = " WHERE is_delete = 0 AND lang = '{$lang}'";
			if ($query) {
				$where .= " AND title LIKE '%{$query}%'";
			}
			// count total
			$count_sql = "SELECT * FROM faq_topic {$where}";
			$data['total'] = $this->db->query($count_sql)->num_rows();
			
			//data sql
			$data_sql = $count_sql." LIMIT ?, ?";
			$param= array($page['start'], $page['end']);
			$data['data'] = $this->db->query($data_sql,$param)->result_array();
			$this->output(true,'success', array(
				'code' => 20000,
				'data' => $data,
			));
		}
	}

	public function getQAList()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input) {
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$lang = $input['lang'] ?? 'tc';
			$page = $this->pagging($page,$limit);
			$topic_id = $input['topic_id'] ?? "";
			$query = $input['query'] ?? "";
			
			// handle filter
			$where = " WHERE faq_content.is_delete = 0 AND faq_content.lang = '{$lang}'";
			if ($topic_id) {
				$where.= " AND faq_topic.id = '{$topic_id}' ";
			}
			if ($query) {
				$where .= " AND (question LIKE '%{$query}%'  OR answer LIKE '%{$query}%') ";
			}

			$count_sql = "SELECT faq_content.*, faq_topic.title FROM faq_content
						  LEFT JOIN faq_topic ON faq_content.topic_id = faq_topic.id
						  {$where}
						  ORDER BY faq_content.id DESC";
			$data['total'] = $this->db->query($count_sql)->num_rows();
			
			//data sql
			$data_sql = $count_sql." LIMIT ?, ?";
			$param= array($page['start'], $page['end']);
			$data['data'] = $this->db->query($data_sql,$param)->result_array();
			$this->output(true,'success', array(
				'code' => 20000,
				'data' => $data,
			));
		}
	}
}

