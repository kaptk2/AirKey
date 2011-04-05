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
		$apInsert = $this->db->insert('ap', $newAPInsert);
		$groupInsert = $this->db->insert('associates', $newGroupInsert);
		return true;
	}

	function apHealth()
	{
		$timestamp = strtotime("5 minutes ago");
		$sql = "SELECT mac, name FROM ap WHERE mac IN (SELECT mac FROM ap WHERE NOT EXISTS (SELECT mac FROM heartbeat WHERE heartbeat.time_stamp > {$timestamp}))";
		$query = $this->db->query($sql);

		$dangers = array();
		foreach ($query->result() as $row)
		{
			$dangers[] = $row;
		}
		return $dangers;
	}

	function showPendingAP()
	{
		$pending =	$this->db->get_where('ap',array('is_active'=>0));
		return $pending->result();
	}

	function showActiveAP($count = "false")
	{
		$query = $this->db->get_where('ap',array('is_active'=>1));
		if ($count == "true")
			$data = $this->db->count_all_results();
		else
			$data = $query->result();
		return $data;
	}

	function showAP($mac)
	{
		$query = $this->db->get_where('ap',array('mac'=>$mac,'is_active'=>1));
		$data = $query->row();
		return $data;

	}

	function approveAP($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('is_active', '1');
		$this->db->update('ap');
	}

	function deactivateAP($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('is_active', '0');
		$this->db->update('ap');
	}

	function deleteAP($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->delete('ap');
	}

	function getGroup($groupName)
	{
		$this->db->select('mac');
		$query = $this->db->get_where('ap',array('groupName' => $groupName));

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data[] = $row->mac;
			}
			return $data;
		}
		return false;
	}

	function changeGroup($mac, $groupName)
	{
		$query = $this->db->get_where('ap',array('mac'=>$mac));

		if($query->num_rows == 1) // Make sure non existant MAC's don't get put in a group
		{
			$this->db->where('mac', $mac);
			$this->db->set('groupName', $groupName);
			$this->db->update('ap');
		}
	}

	function changeName($mac, $name)
	{
		$query =	$this->db->get_where('ap',array('mac'=>$mac, 'is_active'=>'1'));

		if($query->num_rows == 1) // Make sure AP exists and is active
		{
			$this->db->where('mac', $mac);
			$this->db->set('name', $name);
			$this->db->update('ap');
		}
	}
}
?>
