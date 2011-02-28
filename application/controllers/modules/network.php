<?php
	class Network extends CI_Controller
	{
		var $mac;
		var $key;

		function index()
		{
			$this->load->view('registerError_view');
		}

		function auth($mac = '', $key = '')
		{
			if ($mac && $key)
			{
				$this->load->library('Validate');
				$validUser = $this->validate->validateUser($mac, $key);
					if($validUser)
					{
						// Valid User found make the config file
						$this->load->model('modules/modules_model');

						// Find out if a custom version is needed for this mac
						$data['mac'] = $mac;
						$data['moduleName'] = "network";

						$config = $this->modules_model->getConfig($data);
						$this->load->view('modules/module_view', $config);
					}
					else
						$this->load->view('registerError_view');
			}
			else
				$this->load->view('registerError_view');
		}

		function editDefaults()
		{
			$this->load->model('modules/modules_model');
			$this->load->model('aplist_model');

			$groupMembers = $this->aplist_model->getGroup('default');
			$groupMembers[] .= 'default';
			$mac = reset($groupMembers); // MAC of first group member
			$data = $this->modules_model->getConfig($mac);

			if($_POST)
			{
				foreach($groupMembers as $groupMember)
				{
					$update = array (
					'mac' => $groupMember,
					'moduleName' => "network",
					'packageName' => $this->security->xss_clean($this->input->post('packageName')),
					'remoteFile' => $this->security->xss_clean($this->input->post('remoteFile')),
					'localFile' => $this->security->xss_clean($this->input->post('localFile')),
					'command' => $this->security->xss_clean($this->input->post('command'))
				);
				$this->modules_model->changeConfig($update);
				}
			}
			$config['mac'] = "default";
			$config['moduleName'] = "network";

			$data = $this->modules_model->getConfig($config);
			$data['action'] = "editDefaults";
			$data['applyType'] = "group";
			$data['applyTo'] = "default";
			$this->load->view('modules/module_config_view', $data);
		}

		function installModule()
		{
			$this->load->model('modules/modules_model');

			$data['moduleName'] = "network";
			$data['mac'] = "default";
			$data['remoteFile'] = "default";
			$data['localFile'] = "/etc/config/network";
			$data['command'] = '/etc/init.d/network restart';

			$this->modules_model->installModule($data);

			redirect('configure');
		}

		function deleteModule()
		{
			return true; //TODO
		}

		function changeConfig()
		{
		$this->load->model('modules/modules_model');

		if ($_POST && $_POST['applyTo'] != NULL) //make sure that data has been posted
			{
				if ($this->input->post('applyType') == "mac")
				{
					$update = array (
						'mac' => $this->security->xss_clean($this->input->post('applyTo')),
						'moduleName' => "network",
						'packageName' => $this->security->xss_clean($this->input->post('packageName')),
						'remoteFile' => $this->security->xss_clean($this->input->post('remoteFile')),
						'localFile' => $this->security->xss_clean($this->input->post('localFile')),
						'command' => $this->security->xss_clean($this->input->post('command'))
					);
					$this->modules_model->removeGroup($this->input->post('applyTo')); // make sure group is blank
					$this->modules_model->changeConfig($update);
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
						'moduleName' => "network",
						'packageName' => $this->security->xss_clean($this->input->post('packageName')),
						'remoteFile' => $this->security->xss_clean($this->input->post('remoteFile')),
						'localFile' => $this->security->xss_clean($this->input->post('localFile')),
						'command' => $this->security->xss_clean($this->input->post('command'))
					);
					$this->modules_model->changeConfig($update);
					}
				}
			}

			$data['action'] = "changeConfig";
			$data['moduleName'] = "network";
			$this->load->view('modules/module_apply_view', $data);
			$this->load->view('modules/module_config_view', $data);
		}

		function getConfig()
		{
			$this->load->model('modules/modules_model');
			if ($_POST && $_POST['applyTo'] != NULL) //make sure that data has been posted
			{
				if ($this->input->post('applyType') == "mac")
				{
					$mac = array (
						'mac' => $this->security->xss_clean($this->input->post('applyTo')),
						'moduleName' => "network"
					);
					$data = $this->modules_model->getConfig($mac);
					echo json_encode($data);
				}
				else //Group Selected
				{
					$this->load->model('aplist_model');
					$groupName = $this->input->post('applyTo');
					$groupMembers = $this->aplist_model->getGroup($groupName);
					$mac = array (
						'mac' => reset($groupMembers), // MAC of first group member
						'moduleName' => "network"
					);
					$data = $this->modules_model->getConfig($mac);
					echo json_encode($data);
				}
			}
		}
	}
?>
