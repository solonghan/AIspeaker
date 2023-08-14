<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vad_model extends Base_Model {
	
	/**
	 * create vad
	 *
	 * @param vadParam
	 *	*title: (string) vad範本標題
	 *	*description: (string) vad範本說明
	 *  *andriod_thresholds
	 *  *andriod_vadStartBeforeBytesMax
	 *  *andriod_vadStartDelayBytesMax
	 *	*andriod_vadEndDelayBytesMin
	 *	*andriod_vadEndDelayBytesMax
	 *	*ios_thresholds
	 *	*ios_vadStartBeforeBytesMax
	 *	*ios_vadStartDelayBytesMax
	 *	*ios_vadEndDelayBytesMin
	 *	*ios_vadEndDelayBytesMax
	 *
	 */
	public function createVad(array $vadParam)
	{
		$this->db->insert('vad_template', $vadParam);
	}

	public function updateVad($vadId, array $param)
	{
		$this->db->set($param)->where("id", $vadId)->update('vad_template');
	}

	public function getVadData($vadId)
	{
		$result = $this->db->where("id", $vadId)->get('vad_template');
		return $result->row_array();
	}

	public function delVad($vadId)
	{
		$sql = "DELETE FROM vad_template WHERE id =?";
		$param = [$vadId];
		return $this->db->query($sql, $param);
	}
}