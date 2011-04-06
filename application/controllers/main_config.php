<?php
	class Main_config extends CI_Controller
	{
		var $mac;
		var $key;

		function index()
		{
			$data['error_msg'] = "Registration Error";
			$this->load->view('error_view', $data);
		}

		function auth($mac = '', $key = '', $command = '')
		{
			if ($mac && $key)
			{
				$this->load->library('Validate');
				$this->load->helper('file');

				$validUser = $this->validate->validateUser($mac, $key);
					if($validUser)
					{
						// Valid User found make the config file
						$this->load->model('config_model');
						// If the command was not blank then remove it
						if ($command == 'removeCommand')
						{
							$this->config_model->removeCommand($mac);
							return true;
						}

						// Find out if a custom version is needed for this mac
						$data = $this->config_model->getMainConfig($mac);

						$encryptThis = $this->load->view('modules/mainConfig_view', $data, TRUE);

						$password = $this->config->item('networkPassword'); // Get password from config file
						$file = "./static/tmp/$mac";

						if ( ! write_file($file, $encryptThis))
						{
							// File can't be written
							$data['error_msg'] = "File can not be written";
							$this->load->view('error_view', $data);
						}
						else
						{
							system("./static/scripts/encode.sh $password $file");
							// DELETE the file
							unlink($file);
						}
					}
					else
					{
						// Not a valid MAC or key
						$data['error_msg'] = "Invalid MAC or Key";
						$this->load->view('error_view', $data);
					}
			}
			else
				$this->load->view('error_view', "MAC or Key Missing"); // MAC or Key not passed
		}

		function editDefaults()
		{
			$this->load->model('config_model');
			$this->load->model('aplist_model');

			$groupMembers = $this->aplist_model->getGroup('default');
			$groupMembers[] .= 'default';
			$mac = reset($groupMembers); // MAC of first group member
			$data = $this->config_model->getMainConfig($mac);

			if($_POST)
			{
				foreach($groupMembers as $groupMember)
				{
					$update = array (
					'mac' => $groupMember,
					'currentVersion' => $this->security->xss_clean($this->input->post('currentVersion')),
					'modules' => $this->security->xss_clean($this->input->post('modules')),
					'run' => $this->security->xss_clean($this->input->post('run'))
				);
				$this->config_model->changeConfig($update);
				}
			}

			$data = $this->config_model->getMainConfig('default');
			$data['moduleName'] = "mainConfig";
			$data['action'] = "editDefaults";
			$data['applyType'] = "group";
			$data['applyTo'] = "default";
			$this->load->view('modules/mainConfig_config_view', $data);
		}

		function changeConfig()
		{
		$this->load->model('config_model');

		if ($_POST && $_POST['applyTo'] != NULL) //make sure that data has been posted
			{
				if ($this->input->post('applyType') == "mac")
				{
					$update = array (
						'mac' => $this->input->xss_clean($this->input->post('applyTo')),
						'currentVersion' => $this->input->xss_clean($this->input->post('currentVersion')),
						'modules' => $this->input->xss_clean($this->input->post('modules')),
						'run' => $this->input->xss_clean($this->input->post('run'))
					);
					$this->config_model->changeConfig($update);
				}
				else
				{
					// Group Selected
					$this->load->model('aplist_model');
					$groupName = $this->input->post('applyTo');
					$groupMembers = $this->aplist_model->getGroup($groupName);

					foreach($groupMembers as $groupMember)
					{
						$update = array (
						'mac' => $groupMember,
						'currentVersion' => $this->input->xss_clean($this->input->post('currentVersion')),
						'modules' => $this->input->xss_clean($this->input->post('modules')),
						'run' => $this->input->xss_clean($this->input->post('run'))
					);
					$this->config_model->changeConfig($update);
					}
				}
			}

			$data['action'] = "changeConfig";
			$data['moduleName'] = "mainConfig";
			$this->load->view('modules/mainConfig_apply_view',$data);
			$this->load->view('modules/mainConfig_config_view',$data);
		}

		function getConfig()
		{
			$this->load->model('config_model');
			if ($_POST && $_POST['applyTo'] != NULL) //make sure that data has been posted
			{
				if ($this->input->post('applyType') == "mac")
				{
					$mac = $this->input->xss_clean($this->input->post('applyTo'));
					$data = $this->config_model->getMainConfig($mac);
					echo json_encode($data);
				}
				else //Group Selected
				{
					$this->load->model('aplist_model');
					$groupName = $this->input->post('applyTo');
					$groupMembers = $this->aplist_model->getGroup($groupName);
					$mac = reset($groupMembers); // MAC of first group member
					$data = $this->config_model->getMainConfig($mac);
					echo json_encode($data);
				}
			}
		}
	}
?>
