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

		$apInsert = $this->db->insert('ap', $newAPInsert);
		return $apInsert;
	}

	function showPendingAP()
	{
		// Show all AP's who have not been approved yet
		$pending = $this->db->get_where('ap',array('is_active'=>0));
		return $pending->result();
	}

	function activateAP($mac)
	{
		// set the is_active to true and insert data to support AP
		$newGroupInsert = array(
			'mac' => $mac,
			'group_name' => 'default'
		);
		$newConfigInsert = array(
			'mac' => $mac,
			'current_version' => '0',
			'run_command' => 'NULL'
		);
		$newHeartbeatInsert = array(
			'mac' => $mac,
			'time_stamp' => 'NEW'
		);
		$activateMAC = $this->db->update('ap', array('is_active' => 1), array('mac' => $mac));
		$groupInsert = $this->db->insert('associates', $newGroupInsert);
		$configInsert = $this->db->insert('configuration', $newConfigInsert);
		$heartbeatInsert = $this->db->insert('heartbeat', $newHeartbeatInsert);
	}

	function deleteAP($mac)
	{
		// delete the ap from the database
		$this->db->delete('ap', array('mac' => $mac));
	}

	function showActiveAP()
	{
		// Show all AP's who are active
		$this->db->select('*');
		$this->db->where('is_active', '1');
		$this->db->from('ap');
		$this->db->join('associates', 'associates.mac = ap.mac');
		$this->db->join('heartbeat', 'heartbeat.mac = ap.mac');
		$active = $this->db->get();
		return $active->result();
	}

	function showGroup()
	{
		// show all the active AP's
		$this->db->delete('ap', array('mac' => $mac));
	}
}
?>
