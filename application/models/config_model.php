<?php

class Config_model extends CI_Model
{

	function getMainConfig($mac)
	{
		$query = $this->db->get_where('configuration',array('mac'=>$mac));

		if($query->num_rows == 0) // no configu
		{
			//Return the default config
			$query = $this->db->get_where('configuration',array('mac'=>'default'));
		}

		$data = $query->row_array();
		return $data;
	}

	function removeCommand($mac)
	{
		$this->db->where('mac', $mac);
		$this->db->set('run_command', 'NULL');
		$this->db->update('configuration');
	}

}
?>
