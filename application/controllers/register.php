<?php

class Register extends CI_Controller
{

	var $mac;
	var $key;

	function index()
	{
		$this->load->view('registerError_view');
	}

	function auth($mac = '', $key = '', $version = '')
	{
		if ($mac && $key)
		{
			//Get AP Credentials from database
			$this->load->model('config_model');
			$this->load->model('aplist_model');

			$validMAC = $this->aplist_model->validateMAC($mac);
			$versionNumber = $this->config_model->getVersion($mac);

			$this->load->library('Validate');
			$validUser = $this->validate->validateUser($mac, $key);

			if($validMAC) //Check to make sure something is returned
			{
				if($validUser)
				{
					//Get Heartbeat Data

					$tStamp = date("Y-m-d H:i:s");

					$data = array(
						'mac' => $mac,
						'uptime' => $this->input->post('uptime'),
						'version' => $version,
						'tStamp' => $tStamp
					);

					if (isset($_POST['uptime']))
					{
						$this->load->model('heartbeat_model');
						$this->heartbeat_model->heartbeat($data);
					}
						redirect('modules/mainConfig/auth/'.$mac.'/'.$key);
				}
				else
				{
					// Invalid Credinatls passed
					$this->load->view('registerError_view');
				}
			}
			else
			{
				if (preg_match('/^[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}$/i',$mac))
				{ //Test if it is a real MAC
					//The AP is not in database add it to pending table
					$addAP = $this->aplist_model->insertAP($mac, $key);
					if ($addAP)
					{
						$this->load->view('add_view');
					}
				}
				else
				{
					// Invalid MAC address
					$this->load->view('registerError_view');
				}
			}
		}
		else
		{
			// MAC and Key were not passed error out
			$this->load->view('registerError_view');
		}
	}
}
?>
