<?php
	class Manage extends CI_Controller
	{
		function index()
		{
			$this->load->view('header_view');

			$this->load->model('aplist_model');
			$this->load->model('config_model');

			$data['pending'] = $this->aplist_model->showPendingAP();
			$data['activeAP'] = $this->aplist_model->showActiveAP();
			$data['dangers'] = $this->aplist_model->apHealth();

			$data['pendingCmd'] = $this->config_model->pendingCmd();

			$this->load->model('heartbeat_model');
			$data['heartbeat'] = $this->heartbeat_model->showLog();

			$this->load->view('dashboard_view', $data);
			$this->load->view('footer_view');
		}

		function pendingAP()
		{
			if ($_POST) //make sure that data has been posted
			{
				$this->load->model('aplist_model');

				foreach ($this->input->post('approve') as $mac) //Activate the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->aplist_model->approveAP($mac);
				}

				foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->aplist_model->deleteAP($mac);
				}
			}
			redirect('manage');
		}

		function activeAP()
		{
			if ($_POST) //make sure that data has been posted
			{
				$this->load->model('aplist_model');

				foreach ($this->input->post('deactivate') as $mac) //Deactivate the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->aplist_model->deactivateAP($mac);
				}

				foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->aplist_model->deleteAP($mac);
				}
			}
			redirect('manage');
		}
	}
?>
