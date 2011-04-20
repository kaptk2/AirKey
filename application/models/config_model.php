<?php

class Config_model extends CI_Model
{

	function getMainConfig($mac)
	{
		// Build the main configuration file
		$mainConfig = $this->db->get_where('configuration',array('mac' => $mac));
		$data['mainConfig'] = $mainConfig->row();
		$this->db->select('module_name');
		$this->db->from('loads');
		$this->db->where('mac', $mac);
		$this->db->join('associates', 'associates.group_name = loads.group_name');
		$modules = $this->db->get();

		$mtemp = array();
		foreach($modules->result() as $module)
		{
			$mtemp[] = $module->module_name;
		}
		$data['modules'] = $mtemp;
		return $data;
	}

	function updateVersion($mac)
	{
		// Increments the version number
		$sql = "UPDATE configuration SET current_version=current_version+1 WHERE mac='$mac'";
		$query = $this->db->query($sql);
		return $query;
	}

	function getCommand($mac)
	{
		$query = $this->db->get_where('configuration',array('mac'=>$mac));
		return $query->row();
	}

	function setCommand($mac, $command)
	{
		// Sets a new command and updates the version number
		$this->db->where('mac', $mac);
		$query = $this->db->update('configuration', array('run_command' => $command));
		$this->updateVersion($mac); //update the version number
		return $query;
	}

	function removeCommand($mac)
	{
		// Remove a command from the configuration table
		$sql = "UPDATE configuration SET run_command=null WHERE mac='$mac'";
		$query = $this->db->query($sql);
		return $query;
	}
}
?>
