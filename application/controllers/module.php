<?php
	class Module extends CI_Controller
	{
		function index()
		{
			$this->load->model('ap_model');
			$this->load->model('modules_model');
			$this->load->model('heartbeat_model');

			if ($_POST)
			{
				$module_name = $this->input->post('module_name');
				$module_name = $this->security->xss_clean($module_name);
				redirect("module/moduleEdit/$module_name");
			}

			$data['modules'] = $this->modules_model->showModules();

			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = $this->ap_model->pendingCommand();
			$menu['page_name'] = "module";
			$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

			// Build Groups Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('module_view', $data);
			$this->load->view('footer_view');
		}

		function moduleEdit($module_name)
		{
			$this->load->helper('form');

			$this->load->model('ap_model');
			$this->load->model('modules_model');
			$this->load->model('heartbeat_model');

			if (!empty($module_name))
			{
			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = $this->ap_model->pendingCommand();
			$menu['page_name'] = "module";
			$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

			$data['module_name'] = $module_name;
			$data['commands'] = $this->modules_model->getModuleCommands($module_name);
			$data['packages'] = $this->modules_model->getModulePackages($module_name);
			$data['files'] = $this->modules_model->getModuleFiles($module_name);

			// Build Groups Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('moduleEdit_view', $data);
			$this->load->view('footer_view');
			}
			else
			{
				// no module name passed redirect to the index
				redirect("module");
			}
		}

		function createModule()
		{
			$this->load->model('modules_model');

			if ($_POST)
			{
				$module_name = $this->input->post('module_name');
				$module_name = $this->security->xss_clean($module_name);
				$this->modules_model->addModule($module_name);
				redirect("module/moduleEdit/$module_name");
			}
			else
			{
				// no name passed go back to module
				redirect("module");
			}
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

		function editModule()
		{
			$this->load->model('modules_model');

			if ($_POST['old_module'])
			{
				$old_module = $this->input->post('old_module');
				$old_module = $this->security->xss_clean($old_module);

				if (!empty($_POST['module_name']))
				{
					$module_name = $this->input->post('module_name');
					$module_name = $this->security->xss_clean($module_name);
				}

				switch ($_POST['buttons'])
				{
					case 'clone':
						$this->modules_model->cloneModule($old_module, $module_name);
						redirect("module/moduleEdit/$module_name");
						break;
					case 'rename':
						$this->modules_model->renameModule($old_module, $module_name);
						redirect("module/moduleEdit/$module_name");
						break;
					case 'delete this module':
						$this->modules_model->deleteModule($old_module);
						redirect("module");
						break;
				}
			}
			else
			{
				// no name passed go back to module
				echo "no module given";
			}
		}

		function addCommand()
		{
			$this->load->model('modules_model');

			if ($_POST['module_name'])
			{
				$module_name = $this->input->post('module_name');
				$module_name = $this->security->xss_clean($module_name);
				$command = $this->input->post('command');
				$command = $this->security->xss_clean($command);

				$this->modules_model->addCommand($module_name, $command);
			}
			redirect("module/moduleEdit/$module_name");
		}

		function removeCommand()
		{
			$this->load->model('modules_model');

			if ($_POST['module_name'])
			{
				$module_name = $this->input->post('module_name');
				$module_name = $this->security->xss_clean($module_name);

				foreach ($this->input->post('cmd_ids') as $cmd_id)
				{
					//remove command from module
					$this->modules_model->removeCommand($cmd_id);
				}
			}
			redirect("module/moduleEdit/$module_name");
		}

		function addPackage()
		{
			$this->load->model('modules_model');

			if ($_POST['module_name'])
			{
				$module_name = $this->input->post('module_name');
				$module_name = $this->security->xss_clean($module_name);
				$package_name = $this->input->post('package_name');
				$package_name = $this->security->xss_clean($package_name);

				$this->modules_model->addPackage($module_name, $package_name);
			}
			redirect("module/moduleEdit/$module_name");
		}

		function removePackage()
		{
			$this->load->model('modules_model');

			if ($_POST['module_name'])
			{
				$module_name = $this->input->post('module_name');
				$module_name = $this->security->xss_clean($module_name);

				foreach ($this->input->post('pkg_ids') as $pkg_id)
				{
					//remove package from module
					$this->modules_model->removePackage($pkg_id);
				}
			}
			redirect("module/moduleEdit/$module_name");
		}

		function upload()
		{
			$this->load->model('modules_model');

			$ap_location = $this->input->post('ap_location');
			$module_name = $this->input->post('module_name');

			if (empty($ap_location))
			{
				$data['error_msg'] = "You must specify a place to put file on AP. Debug: ".print_r($_POST);

				$this->load->view('error_view', $data);
				return;
			}

			if (empty($module_name)) // A module name is required to continue
			{

				$data['error_msg'] = "You must specify a module name. Debug: $raw ";

				$this->load->view('error_view', $data);
				return;
			}

			$filepath = './modules/'.$module_name;
			//Check to see if module exists
			if (is_dir($filepath)) // The module must already exist to continue
			{
				//Configure the upload option
				$config['upload_path'] = $filepath; //path to store upload
				$config['max_size']	= '100'; // 100KB max file size
				$config['allowed_types'] = '*'; //Allow all filetypes

				$this->load->library('upload', $config); //load config library


				if (!$this->upload->do_upload())
				{
					// TODO Upload was not successful, show errors
					$error = $this->upload->display_errors();
				}
				else
				{
					// File was uploaded successfully
					$data = $this->upload->data();
					// Insert infomation into module database
					$this->modules_model->addFile($module_name, $ap_location, $data['file_name']);
					redirect("module/moduleEdit/$module_name");
				}
			}
			else
			{
				$data['error_msg'] = "Not a valid directory";
				$this->load->view('error_view', $data);
				exit;
			}
		}

		function manageFiles($module_name)
		{
			$this->load->model('modules_model');

			if (!empty($module_name))
			{
				$new_names = $this->input->post('new_names');
				$deleted = $this->input->post('delete');
				$i = 0; // iterator

				foreach ($this->input->post('orig_names') as $name)
				{
					// Iterate though each file in the module
					if ($name != $new_names[$i])
					{
						/* Test to see if the name is  not the same.
						 * If they don't match change it in the database.
						 */
						$this->modules_model->renameFile($module_name, $name, $new_names[$i]);
					}
					if (isset($deleted[$i]))
					{
						//remove file from module
						$this->modules_model->removeFile($module_name, $deleted[$i]);
					}
					$i++;
				}
				redirect("module/moduleEdit/$module_name");
			}
		}
	}
?>
