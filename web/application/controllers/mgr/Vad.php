<?php

use function PHPSTORM_META\map;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Vad extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Vad_model");
	}

	public function delVad()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if (!$input) {
			return;
		}

		$id = $input['id'] ?? "";
		$this->Vad_model->delVad($id);

		$this->output(true, 'success', array(
			'code' => 20000,
		));
	}

	public function createOrUpdateVad()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if (!$input) {
			return;
		}
		$id = $input['id'] ?? "";
		$vadParam = array(
			"title" => $input['title'] ?? "",
			"description" => $input['description'] ?? "",
			"andriod_thresholds" => $input['andriod_thresholds'] ?? "",
			"andriod_vadStartBeforeBytesMax" => $input['andriod_vadStartBeforeBytesMax'] ?? "",
			"andriod_vadStartDelayBytesMax" => $input['andriod_vadStartDelayBytesMax'] ?? "",
			"andriod_vadEndDelayBytesMin" => $input['andriod_vadEndDelayBytesMin'] ?? "",
			"andriod_vadEndDelayBytesMax" => $input['andriod_vadEndDelayBytesMax'] ?? "",
			"ios_thresholds" => $input['ios_thresholds'] ?? "",
			"ios_vadStartBeforeBytesMax" => $input['ios_vadStartBeforeBytesMax'] ?? "",
			"ios_vadStartDelayBytesMax" => $input['ios_vadStartDelayBytesMax'] ?? "",
			"ios_vadEndDelayBytesMin" => $input['ios_vadEndDelayBytesMin'] ?? "",
			"ios_vadEndDelayBytesMax" => $input['ios_vadEndDelayBytesMax'] ?? "",
		);
		
		if ($id) {
			$this->Vad_model->updateVad($id, $vadParam);
		} else {
			$this->Vad_model->createVad($vadParam);
		}

		$this->output(true, 'success', array(
			'code' => 20000,
		));
	}
	
	public function getVadData()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if (!$input) {
			return;
		}
		$id = $input['id'] ?? "";
		$data['data'] = $this->Vad_model->getVadData($id);
		$this->output(true, 'success', array(
			'code' => 20000,
			'data' => $data,
		));
	}

	public function getVadList()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input) {
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$page = $this->pagging($page, $limit);
			$lang = $input['lang'] ?? 'tc';
			$query = $input['query'] ?? "";
			
			// handle filter
			$where = "";
			if ($query) {
				$where .= " WHERE (title LIKE '%{$query}%'  OR description LIKE '%{$query}%' OR id LIKE '%{$query}%') ";
			}

			$count_sql = "SELECT * FROM vad_template
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

