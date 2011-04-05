<?php

class Heartbeat_model extends CI_Model
{

	function heartbeat($data)
	{
		$query = $this->db->get_where('heartbeat',array('mac' => $data['mac']));

		if($query->num_rows == 1) // see if MAC is already in table
		{
			$this->db->where('mac', $data['mac']);
			$this->db->update('heartbeat', $data);
		}
		else // MAC not in table add it
		{
			$this->db->insert('heartbeat', $data);
		}
	}

	function showLog()
	{
		$this->db->order_by("time_stamp", "desc");
		$showLog = $this->db->get('heartbeat', 5);
		return $showLog->result();
	}
}
?>
