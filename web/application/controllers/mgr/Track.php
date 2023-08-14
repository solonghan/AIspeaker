<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Track extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	
	public function getTrackEvent()
	{
		$input = json_decode($this->input->raw_input_stream, true);
		if ($input) {
			$account = $input['account'] ?? "";
			$page = $input['page'] ?? 1;
			$limit = $input['limit'] ?? 10;
			$page = $this->pagging($page, $limit);

			$datetime_type = $input['datetime_type'] ?? "today";
			
			// handle filter
			$where = " WHERE user = ?";
			$param = array($account);

			$date = new DateTime();
			switch ($datetime_type) {
				case "today" :
					$where.= " AND DATE(create_date) = ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "yesterday":
					$date->modify("-1 day");
					$where.= " AND DATE(create_date) >= ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "7D":
					$date->modify("-7 day");
					$where.= " AND DATE(create_date) >= ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "30D":
					$date->modify("-30 day");
					$where.= " AND DATE(create_date) >= ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "3M":
					$date->modify("-3 month");
					$where.= " AND DATE(create_date) >= ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "6M":
					$date->modify("-6 month");
					$where.= " AND DATE(create_date) >= ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "12M":
					$date->modify("-12 month");
					$where.= " AND DATE(create_date) >= ?";
					$param = array_merge($param, [$date->format("Y-m-d")]);
					break;
				case "custom":
					$custom_datetime = $input['custom_datetime'] ?? "";
					$startDate = $custom_datetime["startDate"];
					$endDate = $custom_datetime["endDate"];
					$where.= " AND DATE(create_date) >= ? AND DATE(create_date) <= ?";
					$param = array_merge($param, [$startDate, $endDate]);
					break;
				default:
					break;
			}

			$filterField = [

			];

			$count_sql = "SELECT * FROM track_event
						  {$where}
						  ORDER BY id DESC";
			$data['total'] = $this->db->query($count_sql, $param)->num_rows();
			
			//data sql
			$data_sql = $count_sql." LIMIT ?, ?";
			$param= array_merge($param, array($page['start'], $page['end']));
			
			$data['data'] = $this->db->query($data_sql,$param)->result_array();
			$this->output(true, 'success', array(
				'code' => 20000,
				'data' => $data,
			));
		}
	}

}

