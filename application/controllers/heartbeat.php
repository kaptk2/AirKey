<?php
	class HeartBeat extends CI_Controller
	{
		var $mac;
		var $key;

		function index()
		{
			$data['error_msg'] = "Registration Error";
			$this->load->view('error_view', $data);
		}

		function process()
		{
			$mac= $this->input->post('mac');
			$key = $this->input->post('key');

			if ($mac && $key)
			{
				$this->load->library('Validate');
				$validUser = $this->validate->validateUser($mac, $key);
					if($validUser)
					{
						return true;
					}
			}
			$data['error_msg'] = "Not Authorized";
			$this->load->view('error_view', $data);
		}

	}
?>
