<?php

class Register extends CI_Controller
{

	var $mac;
	var $key;

	function index()
	{
		$data['error_msg'] = "Registration Error";
		$this->load->view('error_view', $data);
	}

	function auth($mac = '', $key = '', $version = '')
	{
		if ($mac && $key) //Both $mac and $key are required if not show error message
		{
			//Get AP Credentials from database
			$this->load->model('ap_model');
			$validMAC = $this->ap_model->validateMAC($mac);

			if($validMAC) //Check to make sure something is returned
			{
				//Test to see if a valid user name and password were passed
				$this->load->library('Validate');
				$validUser = $this->validate->validateUser($mac, $key);

				if($validUser)
				{
					$time_stamp = time();
					if (isset($_POST['uptime']))
					{
						//Get Heartbeat Data
						$data = array(
							'mac' => $mac,
							'uptime' => $this->input->post('uptime'),
							'ap_version' => $this->input->post('ap_version'),
							'time_stamp' => $time_stamp
						);
					}
					else
					{
						$data = array(
							'mac' => $mac,
							'time_stamp' => $time_stamp
						);
					}

					$this->load->model('heartbeat_model');
					$this->heartbeat_model->heartbeat($data);
					//All is well display the config file
					redirect('main_config/auth/'.$mac.'/'.$key);
				}
				else
				{
					// Invalid credentials passed
					$data['error_msg'] = "Invalid Credentials";
					$this->load->view('error_view', $data);
				}
			}
			else
			{
				if (preg_match('/^[a-f0-9]{12}$/i',$mac)) //Test if it is a valid username
				{
					//The AP is not in database add it to pending table
					$addToAP = $this->ap_model->insertAP($mac, $key);

					if ($addToAP)
					{
						$data['error_msg'] = "AP does not exist. Adding to pending table";
						$this->load->view('error_view', $data);
					}
					else
					{
						$data['error_msg'] = "Error Inserting Data into database";
						$this->load->view('error_view', $data);
					}
				}
				else
				{
					// Invalid MAC address
					$data['error_msg'] = "Invalid MAC address";
					$this->load->view('error_view', $data);
				}
			}
		}
		else
		{
			// MAC and Key were not passed error out
			$data['error_msg'] = "MAC or Key not passed";
			$this->load->view('error_view', $data);
		}
	}
}
?>
