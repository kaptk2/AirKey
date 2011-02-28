<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validate {

	function validateUser($mac, $key)
	{
		$CI =& get_instance();
		$CI->load->database();

		$CI->db->where('mac', $mac);
		$CI->db->where('ap_key', md5($key));
		$CI->db->where('isActive', 1);
		$query = $CI->db->get('apList');
		if($query->num_rows == 1)
		{
			return true;
		}
		return false;
	}

}

?>
