<?php
class ap_model extends CI_Model
{

	function validateMAC($mac)
	{
		$this->db->where('mac', $mac);
		$query = $this->db->get('ap');
		if($query->num_rows == 1)
		{
			return true;
		}
	}

	function insertAP($mac, $key)
	{
		$newAPInsert = array(
			'mac' => $mac,
			'ap_key' => md5($key),
			'is_active' => '0'
		);
		$newGroupInsert = array(
			'mac' => $mac,
			'group_name' => 'default'
		);
		$newConfigInsert = array(
			'mac' => $mac,
			'current_version' => '0',
			'run_command' => 'NULL'
		);
		$apInsert = $this->db->insert('ap', $newAPInsert);
		$groupInsert = $this->db->insert('associates', $newGroupInsert);
		$configInsert = $this->db->insert('configuration', $newConfigInsert);
		if ($apInsert && $groupInsert && $configInsert)
			$data = true; // Data was inserted successfully
		else
			$data = false;
		return $data;
	}

}
?>
