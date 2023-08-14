<?php


require_once APPPATH . '/libraries/Request.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers,X-Token");
header('Access-Control-Allow-Credentials: true');

class Api extends Base_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->load->model('Jwt_model');
		$this->load->model("Trace_event_model");
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

	/**
	 * 客服中心 類別list
	 */
	public function get_problem_list(){
		$sql = "SELECT id as problem_id ,icon,title FROM crm_topic WHERE is_delete = 0";
		$result = $this->db->query($sql)->result_array();
		if($result){
			$this->output(true,'success', array(
				'data' => $result
			));
		}
	}
	
	/**
	 * 
	 */
	public function set_contactus(){
		$request = new Request($this->input->post());
		$param['language'] = $request->getData("language",true,"language 不能為空");
		$param['name'] = $request->getData("name",false,"name 不能為空");
		$param['email'] = $request->getData("email",true,"email 不能為空");
		$param['phone'] = $request->getData("phone",true,"phone 不能為空");
		$param['topic_id'] = $request->getData("problem_id",true,"problem_id 不能為空");
		$param['problem_message'] = $request->getData("problem_message",true,"problem_message 不能為空");
		$param['device_system'] = $request->getData("device_system",false,"device_system 不能為空");
		$param['device_info'] = $request->getData("device_info",true,"device_info 不能為空");
		$param['case_status'] = 'unprocessed';

		$this->db->trans_start();
		$this->db->insert("crm_record",$param);
		$id = $this->db->insert_id();

		$param['problem_title'] = $this->get_contatusus_problem_title($param['topic_id']);
		if(empty($param['problem_title'])){
			$this->db->trans_rollback();
			$this->output(false,'problem_id data not found');
		}

		$curl_resut = $this->send_contactus_mail($param);
		$contact_id = $curl_resut['id'];

		$this->db->set('contact_id', $contact_id)->where('id',$id)->update('crm_record');
		$res = $this->db->trans_complete();

		if($res){
			$this->output(true,'success');
		}else{
			$this->output(false,'error');
		}
	}

	private function get_contatusus_problem_title($id){
		$sql = "SELECT * FROM crm_topic WHERE id=? AND is_delete = 0 ";
		$param = array($id);
		$result = $this->db->query($sql, $param)->row_array();
		$title = $result['title'] ?? "";
		return $title;
	}
	private function send_contactus_mail($data){
		$data['model'] = "Contactus";
		$data['command'] = "Send";
		$url = "https://www.apreventmed.com:2096/messenger/contact/send_contactus_mail.php";
		$result = $this->curl_post($url,$data);
		$result = json_decode($result,true);
		if($result['status'] == 0){
			$this->db->trans_rollback();
			$this->output(false,'send_contactus_mail fail');
		}

		return $result;
	}

	/**
	 * 常見問題類別列表
	 */
	public function get_faq_category_list()
	{
		$lang = $this->input->post('lang') ?? "tc";
		$sql = "SELECT id as category_id ,icon,title FROM faq_topic WHERE is_delete = 0 AND lang = ?";
		$result = $this->db->query($sql, [$lang])->result_array();
		if ($result) {
			$this->output(true,'success',array(
				'data' => $result,
			));
		}
	}

	/**
	 * 常見問題內容
	 */
	public function get_faq_content(){
		$category_id = $this->input->post('category_id') ?? "";
		$sql = "SELECT id, question, answer FROM faq_content 
				WHERE is_delete = 0 AND topic_id = ?";
		$param = array($category_id);
		$result = $this->db->query($sql, $param)->result_array();
		
		$this->output(true,'success',array(
			'data' => $result ?? [],
		));
		
	}
	/**
	 * 最新消息
	 */
	public function get_news_list()
	{
		$lang = $this->input->post('lang') ?? "tc";
		$sql = "SELECT id,title,content,lang FROM news WHERE is_delete = 0 AND lang = ?";
		$param = [$lang];
		$result = $this->db->query($sql, $param)->result_array();
		$this->output(true, 'success',array(
			'data' => $result ?? [],
		));
	}
	/**
	 * VAD設定範本
	 */
	public function get_vad_list()
	{
		$sql = "SELECT * FROM vad_template;";
		$result = $this->db->query($sql)->result_array();
		$this->output(true,'success',array(
			'data' => $result ?? [],
		));
	}

	public function track_event()
	{
		$account = $this->input->post('account') ?? "";
		$event = $this->input->post('event') ?? "";
		
		$app_version = $this->input->post('app_version');
		$app_lang = $this->input->post('app_lang');
		$device_info = $this->input->post('device_info');
		$device_firmware = $this->input->post('device_firmware');
		
		$msg = $this->Trace_event_model->traceEvent($account, $event, $app_version, $app_lang, $device_info, $device_firmware);

		$this->output(true, $msg, array(
			
		));
	}

	public function track_page()
	{
		$account = $this->input->post('account') ?? "";
		$page = $this->input->post('page') ?? "";
		$type = $this->input->post('type') ?? "";
		$page_stay_time = $this->input->post('page_stay_time') ?? 0;

		if ($type == "access") {
			$msg = $this->Trace_event_model->tracePage($account, $page, null);
		} elseif ($type == "stay") {
			$msg = $this->Trace_event_model->tracePage($account, $page, $page_stay_time);
		} else {
			$this->Trace_event_model->tracePage($account, $page, null);
			$msg = "type未定義";
		}

		$this->output(true, $msg, array(
			
		));
	}
}

