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

		function removeModuleFromGroup($group_name, $module_name)
		{
			// Delete the module associated with a group
			$query = $this->db->delete('loads', array('group_name' => $group_name, 'module_name' => $module_name));
			//TODO increment version number
			return true;
		}

		function addModuleToGroup($group_name, $module_name)
		{
			// Add a module to a group
			$newModuleInsert = array(
				'group_name' => $group_name,
				'module_name' => $module_name
			);
			$moduleInsert = $this->db->insert('loads', $newModuleInsert);
			//TODO increment version number
			return $moduleInsert;
		}

		function updateModuleVersion($module_name)
		{
			// update the module and config version number 
			$sql1 = "SELECT c.mac FROM configuration c ";
			$sql1 .= "LEFT JOIN associates a ON c.mac = a.mac ";
			$sql1 .= "LEFT JOIN loads l ON a.group_name = l.group_name ";
			$sql1 .= "LEFT JOIN modules m ON l.module_name = m.module_name ";
			$sql1 .= "WHERE m.module_name='$module_name'";
			$query1 = $this->db->query($sql1);
			// The query gets all the MACs that use the module
			$results = $query1->result();

			$this->load->model('config_model');
			foreach ($results as $result)
			{
				// Update the config version number
				$this->config_model->updateVersion($result->mac);
			}

			$sql2 = "UPDATE modules SET module_version=module_version+1 WHERE module_name='$module_name'";
			$query2 = $this->db->query($sql2);
			return true;
		}

		function addModule($module_name)
		{
			// create a new module
			$newModuleInsert = array(
				'module_name' => $module_name,
				'module_version' => '0'
			);
			if (mkdir("./modules/$module_name", 0755))
			{
				$moduleInsert = $this->db->insert('modules', $newModuleInsert);
				return true;
			}
			die("Can't create a directory check permissions");
		}

		function deleteModule($module_name)
		{
			// delete a module
			if (rmdir("./modules/$module_name"))
			{
				$this->db->delete('modules',
					array('module_name' => $module_name));
				return true;
			}
			// No files were found with this module
			// Delete it from the database and throw error
			$this->db->delete('modules',
					array('module_name' => $module_name));
			$data = "Only removed from database, the files could not be removed <br />";
			$data .= "This may be because the file does not exist or the webserver";
			$data .= " does not have permission to delete these files.<br />";
			$data .= "The directory we tried to delete is: ".getcwd()."/modules/$module_name";
			die($data);
		}

		function renameModule($old_module, $new_module)
		{
			// rename a module
			if (rename("./modules/$old_module","./modules/$new_module"))
			{
				$this->db->where('module_name', $old_module);
				$this->db->update('modules',
					array('module_name' => $new_module));
				return true;
			}
			$data = "Can't rename file, check permissions <br />";
			$data .= "The directory we tried to rename is: ".getcwd()."/modules/$new_module";
			die($data);
		}

		function cloneModule($old_module, $new_module)
		{
			// clone a module
			$this->addModule($new_module);

			// Copy the directory structure and files
			if (copy("./modules/$old_module","./modules/$new_module"))
			{
				// Get Files from the old module and insert them under new module
				$new_files = $this->getModuleFiles($old_module);
				foreach ($new_files as $file)
				{
					$this->addFile($new_module, $file->local_file, $file->remote_file);
				}
				// Get commands from the old module and insert them under new module
				$new_commands = $this->getModuleCommands($old_module);
				foreach ($new_commands as $command)
				{
					$this->addCommand($new_module, $command->command);
				}
				// Get packages from the old module and insert them under new module
				$new_packages = $this->getModulePackages($old_module);
				foreach ($new_commands as $command)
				{
					$this->addCommand($new_module, $command->command);
				}
				return true;
			}
			$data = "Can't add a new directory to clone to, check permissions";
			$data .= "The directory we tried to add is: ".getcwd()."/modules/$new_module";
			die($data);
		}

		function addCommand($module_name, $command)
		{
			// add a command to a module
			$newCommandInsert = array(
				'module_name' => $module_name,
				'command' => $command
			);
			$commandInsert = $this->db->insert('module_commands', $newCommandInsert);
			$this->updateModuleVersion($module_name);
			return $commandInsert;
		}

		function removeCommand($id)
		{
			// remove a command from a module
			return $this->db->delete('module_commands', array('id' => $id));
		}

		function addPackage($module_name, $package_name)
		{
			// add a package to a module
			$newPackageInsert = array(
				'module_name' => $module_name,
				'package_name' => $package_name
			);
			$packageInsert = $this->db->insert('module_packages', $newPackageInsert);
			$this->updateModuleVersion($module_name);
			return $packageInsert;
		}

		function removePackage($id)
		{
			// remove a package from a module
			return $this->db->delete('module_packages', array('id' => $id));
		}

		function addFile($module_name, $remote_file, $local_file)
		{
			// adds a file to a moudule
			$newFileInsert = array(
				'module_name' => $module_name,
				'remote_file' => $remote_file,
				'local_file' => $local_file
			);

			$fileInsert = $this->db->insert('module_files', $newFileInsert);
			$this->updateModuleVersion($module_name);
			return $fileInsert;
		}

		function removeFile($module_name, $local_file)
		{
			// Build File Location on Disk
			$file_name = "./modules/".$module_name."/".$local_file;
			// removes a file from a moudule
			if (unlink("$file_name"))
			{
				// if the deletion is succesful remove from database
				$this->db->where('module_name', $module_name);
				$this->db->where('local_file', $local_file);
				$this->db->delete('module_files');
				return true;
			}
			$data = "Unable to remove the file, check permissions";
			$data .= "The file we tried to delete is: ".getcwd()."$file_name";
			die ($data);
		}

		function renameFile($module_name, $old_file, $new_file)
		{
			// rename a file in a module
			$this->db->where('module_name', $module_name);
			$this->db->where('remote_file', $old_file);
			$this->db->update('module_files', array('remote_file' => $new_file));
			return true;
		}

		function getModuleFiles($module_name)
		{
			// get the files associated with a module
			$query = $this->db->get_where('module_files', array('module_name' => $module_name));
			return $query->result();
		}

		function getModuleCommands($module_name)
		{
			// get the commands associated with a module
			$query = $this->db->get_where('module_commands', array('module_name' => $module_name));
			return $query->result();
		}

		function getModulePackages($module_name)
		{
			// get the packages associated with a module
			$query = $this->db->get_where('module_packages', array('module_name' => $module_name));
			return $query->result();
		}

		function getModuleVersion($module_name)
		{
			// Gets the version number of a module
			$this->db->select('module_version');
			$query = $this->db->get_where('modules', array('module_name' => $module_name));
			if ($query->num_rows() > 0)
			{
				 $row = $query->row();
				 return $row->module_version;
			}
			return false; //Not a valid module name
		}
	}
?>
