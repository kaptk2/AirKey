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

	function removeCommand($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('run_command', '');
		$this->db->update('configuration');
	}

	function getCommand($mac)
	{
		$query = $this->db->get_where('configuration',array('mac'=>$mac));
		return $query->row();
	}

	function setCommand($mac, $command)
	{
		// Sets a new command and updates the version number
		$sql = "UPDATE configuration SET current_version=current_version+1, run_command='$command' WHERE mac='$mac'";
		$query = $this->db->query($sql);
		return $query;
	}
}
?>
