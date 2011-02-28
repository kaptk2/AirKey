<?php

class Config_model extends CI_Model
{

	function pendingCmd()
	{
		$this->db->query('SELECT run FROM config WHERE run IS NOT NULL');
		$data = $this->db->count_all_results();
		return $data;
	}

	function getVersion($mac)
	{
		$this->db->select('currentVersion');

		$query = $this->db->get_where('config',array('mac' => $mac));

		if($query->num_rows == 1)
		{
			$row = $query->row();
			return $row->currentVersion;
		}
		$this->db->select('currentVersion');
		$query = $this->db->get_where('config',array('mac' => 'default'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->currentVersion;
		}
		return 0;
	}

	function getMainConfig($mac)
	{
		$query = $this->db->get_where('config',array('mac'=>$mac));

		if($query->num_rows == 0)
		{
			//Return the default config
			$query = $this->db->get_where('config',array('mac'=>'default'));
		}

		$data = $query->row_array();
		return $data;
	}

	function installModule($data)
	{
		$data = array(
			'mac' => 'default',
			'currentVersion' => '0',
			'modules' => ''
		);
		$this->db->insert('config', $data);
	}

	function deleteModule($data)
	{
		$this->db->delete('config', $data);
	}

	function changeConfig($update)
	{
		$query = $this->db->get_where('config',array('mac'=>$update['mac']));

		if($query->num_rows == 0) // If the data does not exist
			$this->db->insert('config', $update); // Insert it
		else // else update it
		{
			$this->db->where('mac', $update['mac']);
			$this->db->update('config', $update);
		}
	}

	function removeCommand($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('run', ' ');
		$this->db->update('config');
	}

	function deleteConfig($update)
	{
		$this->db->delete('config', $update);
	}
}
?>
