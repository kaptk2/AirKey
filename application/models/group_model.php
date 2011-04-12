<?php
class group_model extends CI_Model
{

	function showGroups()
	{
		$query = $this->db->get('groups');
		return $query->result();
	}

	function getGroups($mac)
	{
		$query = $this->db->get_where('associates', array('mac' => $mac));
		if ($query->num_rows() > 0)
			$ret = $query->row();
		return $ret->group_name; //return the group the ap is in
	}
}
?>
