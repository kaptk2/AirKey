<?php

class Build extends CI_Controller
{

	function index()
	{
		$data['error_msg'] = "Module Build Error";
		$this->load->view('error_view', $data);
	}

	function buildModule($module_name, $mac, $key)
	{
		// Build the module configuration file for the selected module
		if ($module_name && $mac && $key)
		{
			//Test to see if a valid user name and password were passed
			$this->load->library('Validate');
			$validUser = $this->validate->validateUser($mac, $key);

			if($validUser)
			{
				// Start building module
				$this->load->model('modules_model');
				$data['name'] = $module_name;
				$data['version'] = $this->modules_model->getModuleVersion($module_name);
				$data['packages'] = $this->modules_model->getModulePackages($module_name);
				$data['files'] = $this->modules_model->getModuleFiles($module_name);
				$data['commands'] = $this->modules_model->getModuleCommands($module_name);
				//print_r($data); //DEBUG FIXME
				$this->load->view('build_module_view', $data);
			}
			else
			{
				// Invalid credentials
				$data['error_msg'] = "Invalid credentials";
				$this->load->view('error_view', $data);
			}
		}
		else
		{
			// Invalid credentials or incomplete data passed
			$data['error_msg'] = "Incomplete Data";
			$this->load->view('error_view', $data);
		}
	}
}
