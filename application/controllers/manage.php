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
			$menu['pageName'] = "manage";
			$menu['totalAP'] = "22";
			$menu['pending'] = "2";

			// Build Dashboard Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
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

		function setCommand()
		{
			$this->load->model('config_model');

			if ($_POST) //make sure that data has been posted
			{
				// get and sanatize variables
				$mac = $this->input->post('mac');
				$mac = $this->security->xss_clean($mac);
				$command = $this->input->post('command');
				$command = $this->security->xss_clean($command);

				$this->config_model->setCommand($mac, $command);
			}
			redirect('manage/editAP/'.$mac);
		}

		function editAP($mac)
		{
			// Edit name, location, notes and group memebership
			$this->load->model('ap_model');
			$this->load->model('group_model');
			$this->load->model('config_model');

			$data['mac'] = $mac;
			$data['name'] = $this->ap_model->getName($mac);
			$data['notes'] = $this->ap_model->getNotes($mac);
			$data['location'] = $this->ap_model->getLocation($mac);

			$data['groups'] = $this->group_model->showGroups();
			$data['currentGroup'] = $this->group_model->getGroup($mac);

			$data['command'] = $this->config_model->getCommand($mac);

			if ($_POST) //make sure that data has been posted
			{
				// get and clean variables
				$mac = $this->input->post('mac');
				$mac = $this->security->xss_clean($mac);
				$ap_name = $this->input->post('ap_name');
				$ap_name = $this->security->xss_clean($ap_name);
				$group = $this->input->post('group');
				$notes = $this->input->post('notes');
				$notes = $this->security->xss_clean($notes);
				$location = $this->input->post('location');
				$location = $this->security->xss_clean($location);

				if (!empty($group)) // Group can't be empty
				{
					// update ap's group
					$group = $this->security->xss_clean($group);
					$this->group_model-> updateGroupMember($mac, $group);

					if (!empty($ap_name)) // if the ap name is set update it
						$this->ap_model->setName($mac, $ap_name);

					if (!empty($notes)) // if the notes are set update it
						$this->ap_model->setNotes($mac, $notes);

					if (!empty($location)) // if the location is set update it
						$this->ap_model->setLocation($mac, $location);
				}
				redirect('manage/editAP/'.$mac);
			}

			$menu['pageName'] = "manage";
			$menu['totalAP'] = "22";
			$menu['pending'] = "2";

			// Build editAP Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('editAP_view', $data);
			$this->load->view('footer_view');
		}
	}
?>
