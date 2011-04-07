<?php
	class Manage extends CI_Controller
	{
		function index()
		{
			$this->load->model('ap_model');
#			$this->load->model('config_model');

			$data['pending'] = $this->ap_model->showPendingAP();
			$data['active'] = $this->ap_model->showActiveAP();
#			$data['dangers'] = $this->aplist_model->apHealth();

#			$data['pendingCmd'] = $this->config_model->pendingCmd();

#			$this->load->model('heartbeat_model');
#			$data['heartbeat'] = $this->heartbeat_model->showLog();
			// Build Dashboard Page
			$this->load->view('header_view');
			$this->load->view('menu_view');
			$this->load->view('dashboard_view', $data);
			$this->load->view('footer_view');
		}

		function managePending()
		{
			if ($_POST) //make sure that data has been posted
			{
				$this->load->model('ap_model');

				foreach ($this->input->post('approve') as $mac) //Activate the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->ap_model->activateAP($mac);
				}

				foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->ap_model->deleteAP($mac);
				}
			}
			redirect('manage');
		}

		function deleteActive()
		{
			if ($_POST) //make sure that data has been posted
			{
				$this->load->model('ap_model');

				foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
				{
					$mac = $this->security->xss_clean($mac);
					$this->ap_model->deleteAP($mac);
				}
			}
			redirect('manage');
		}

		function editAP($mac)
		{
			// Edit name and group memebership
			//TODO
			$data['mac'] = $mac;
			// Build editAP Page
			$this->load->view('header_view');
			$this->load->view('menu_view');
			$this->load->view('editAP_view', $data);
			$this->load->view('footer_view');
		}
	}
?>
