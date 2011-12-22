<?php
	class Cron extends CI_Controller
	{

		function index()
		{
			$data['error_msg'] = "No Access Allowed";
			$this->load->view('error_view', $data);
		}

		function getAlerts()
		{

			$this->load->model('heartbeat_model');

			$this->load->helper('email');

			$errors= $this->heartbeat_model->emailStatus();

			if ($errors > 0)
			{
				$message = "You have $errors error(s) with your network.";
				send_email('andrew@rocky.edu', 'Network Trouble', $message);
			}
		}

	}
?>
