<?php
class Request {
    protected $post_data;

    private $require_msg;

    public function __construct($post_data)
    {
        $this->post_data = $post_data;
        $this->require_msg = "參數錯誤";
    }

    /**
     * get data by key
     * 
     * @param key 
     * 
     * @param require is require not empty
     * 
     * @param require_msg 
     */
    public function getData($key, $require=false, $require_msg=""){
        $data = $this->post_data[$key] ?? "";

        if($require && empty($data)){
            $this->output(false,$require_msg);
        }

        return $data;
    }

    /**
     * output
     */
    private function output($code, $msg, $pass_data = FALSE){
		$data = array();
        if($code === FALSE && $msg == ""){
            $msg = $this->require_msg;
        }

		if ($pass_data === FALSE) {
			$data = array("status"=>$code, "msg"=>$msg);
		}else{
			$data = array_merge($data, $pass_data);
			$data['status'] = $code;
			$data['msg'] = $msg;
		}

		echo json_encode($data);
		exit();
	}


}