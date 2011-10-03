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
				$this->load->library(array('validate','encrypt'));
				$this->load->helper('file');

				$validUser = $this->validate->validateUser($mac, $key);
					if($validUser)
					{
						// Valid User found make the config file
						$this->load->model('config_model');
						// If the $command has run then remove it
						if ($command == 'removeCommand')
						{
							$this->config_model->removeCommand($mac);
							return true;
						}

						// Get the data to build the configuration file
						$data['config'] = $this->config_model->getMainConfig($mac);

						//$this->load->view('mainConfig_view', $data); // DEBUG Plain Text view

						$encrypt = $this->load->view('mainConfig_view', $data, TRUE);

						echo $this->encrypt->ssl_encode($encrypt);
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
	}
?>
