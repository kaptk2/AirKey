<?php
class aplist_model extends CI_Model
{

	function activeAP()
	{
		$this->db->get_where('apList',array('isActive'=>1));
		$data = $this->db->count_all_results();
		return $data;
	}

	function validateMAC($mac)
	{
		$this->db->where('mac', $mac);
		$query = $this->db->get('apList');
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
			'isActive' => '0'
		);
		$insert = $this->db->insert('apList', $newAPInsert);
		return $insert;
	}
	
	function apHealth()
	{
		$timestamp = strtotime("5 minutes ago");
		$sql = "SELECT mac, name FROM apList WHERE mac IN (SELECT mac FROM apList WHERE NOT EXISTS (SELECT mac FROM heartbeat WHERE heartbeat.tStamp > {$timestamp}))";
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
		$pending =	$this->db->get_where('apList',array('isActive'=>0));
		return $pending->result();
	}

	function showActiveAP()
	{
		$query = $this->db->get_where('apList',array('isActive'=>1));
		$data = $query->result();
		return $data;
	}

	function showAP($mac)
	{
		$query = $this->db->get_where('apList',array('mac'=>$mac,'isActive'=>1));
		$data = $query->row();
		return $data;

	}

	function approveAP($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('isActive', '1');
		$this->db->update('apList');
	}

	function deactivateAP($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('isActive', '0');
		$this->db->update('apList');
	}

	function deleteAP($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->delete('apList');
	}

	function getGroup($groupName)
	{
		$this->db->select('mac');
		$query = $this->db->get_where('apList',array('groupName' => $groupName));

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
		$query = $this->db->get_where('apList',array('mac'=>$mac));

		if($query->num_rows == 1) // Make sure non existant MAC's don't get put in a group
		{
			$this->db->where('mac', $mac);
			$this->db->set('groupName', $groupName);
			$this->db->update('apList');
		}
	}

	function changeName($mac, $name)
	{
		$query =	$this->db->get_where('apList',array('mac'=>$mac, 'isActive'=>'1'));

		if($query->num_rows == 1) // Make sure AP exists and is active
		{
			$this->db->where('mac', $mac);
			$this->db->set('name', $name);
			$this->db->update('apList');
		}
	}
}
?>
