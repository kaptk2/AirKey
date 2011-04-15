<?php

class Modules_model extends CI_Model
{
	function showModules()
	{
		// Return all of the modules currently installed
		$query = $this->db->get('modules');
		return $query->result();
	}

	function getModules($group_name)
	{
		// Get the modules associated with a group
		$query = $this->db->get_where('loads', array('group_name' => $group_name));
		return $query->result();
	}

	function removeModule($group_name, $module_name)
	{
		// Delete the module associated with a group
		$query = $this->db->delete('loads', array('group_name' => $group_name, 'module_name' => $module_name));
		return true;
	}

	function addModule($group_name, $module_name)
	{
		// Add a module to a group
		$newModuleInsert = array(
			'group_name' => $group_name,
			'module_name' => $module_name
		);
		$moduleInsert = $this->db->insert('loads', $newModuleInsert);
		return $moduleInsert;
	}
}
?>
