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

		function getAllModules()
		{
			// Get all of the avaiable modules
			$query = $this->db->get('modules');
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

		function updateModuleVersion($module_name)
		{
			// update the module version number
			$sql = "UPDATE modules SET module_version=current_version+1 WHERE module_name='$module_name'";
			$query = $this->db->query($sql); //Update the version number
			return $query;
		}

		function buildModule($module_name)
		{
			// function to build a module file
#			$module_config = $this->db->get_where('modules',array('module_name' => $module_name));
#			$data['module_config'] = $module_config->row();
			$this->db->select('*');
			$this->db->from('modules');
			$this->db->where('modules.module_name', $module_name);
			// get the commands and packages assocated with the module
			$this->db->join('module_commands', 'modules.module_name = module_commands.module_name', 'left');
			// get the files associated with the module
			$this->db->join('module_files', 'modules.module_name = module_files.module_name', 'left');
			$query = $this->db->get();
			return $query->result();
		}
	}
?>
