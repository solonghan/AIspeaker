<?php

use function PHPSTORM_META\map;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Crm extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function createTopic(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$icon = $input['icon'] ?? "" ;
			$title = $input['title'] ?? "" ;
			$data = array('icon' => $icon, 'title' => $title);
			$this->db->insert('crm_topic',$data);
			$this->output(true,'success',array(
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

			$sql = "UPDATE crm_topic SET icon = ? , title =? WHERE id = ?";
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
			$sql = "UPDATE crm_topic SET is_delete = 1 WHERE id = ?";
			$param = array($id);
			$this->db->query($sql, $param);
			$this->output(true,'success',array(
				'code' => 20000,
			));
		}
	}

	public function getTopicOption(){
		$sql = "SELECT * FROM crm_topic WHERE is_delete = 0";
		$data['data'] = $this->db->query($sql)->result_array();
		$this->output(true,'success', array(
			'code' => 20000,
			'data' => $data,
		));
	}

	public function getTopicList(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$page = $this->pagging($page,$limit);

			$query = $input['query'] ?? "";
			
			// craft where
			$where = " WHERE is_delete = 0 ";
			if($query){
				$where .= " AND title LIKE '%{$query}%'";
			}
			// count total
			$count_sql = "SELECT * FROM crm_topic {$where}";
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

	public function getRecordList(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$page = $this->pagging($page,$limit);
			$status = $input['status'] ?? "";
			$category = $input['category'] ?? "";
			$query = $input['query'] ?? "";
			
			// handle filter
			$where = " WHERE crm_record.is_delete = 0 ";
			if($status){
				$where.= " AND case_status = '{$status}' ";
			}
			if($category){
				$where.= " AND topic_id = '{$category}' ";
			}
			if($query){
				$where .= " AND (problem_message LIKE '%{$query}%'  OR contact_id LIKE '%{$query}%') ";
			}
			// count total
			$count_sql = "SELECT *,crm_record.id as id FROM crm_record 
						  LEFT JOIN crm_topic ON crm_record.topic_id = crm_topic.id
						 {$where} 
						 ORDER BY crm_record.id DESC";
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

	public function setContactusStatus(){
		$input = json_decode($this->input->raw_input_stream,true);
		if($input){
			$record_id = $input['record_id'] ?? '';
			$sql = "SELECT * FROM crm_record WHERE is_delete = 0 AND id = ?";
			$param = array($record_id);
			$record_data = $this->db->query($sql, $param)->row_array();
			$this->load->model('Aispeaker_model');
			$result = $this->Aispeaker_model->send_mail($record_data);
			if($result == true){
				$this->db->set(array('case_status' => 'processed'))->where('id',$record_id)->update('crm_record');
				$this->output(true,'success', array(
					'code' => 20000,
				));
			}
		}
	}


}

