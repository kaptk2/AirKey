<?php
	class Manage extends CI_Controller
	{
		function index($page_num = 0)
		{
			$this->load->model('ap_model');
			$this->load->model('heartbeat_model');

			$this->load->library('pagination');
			//Setup pagination
			$per_page = 5;
			$config['base_url'] = site_url('manage/index');
			$config['total_rows'] = $this->ap_model->activeAPCount();
			$config['per_page'] = $per_page;
			$this->pagination->initialize($config);

			$data['pending'] = $this->ap_model->showPendingAP();
			$data['active'] = $this->ap_model->showActiveAP($per_page,$page_num);
			$data['pages'] = $this->pagination->create_links();

			$menu['page_name'] = "manage";
			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = $this->ap_model->pendingCommand();
			$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

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

				$approve = $this->input->post('approve');
				$delete = $this->input->post('delete');


				if (!empty($approve))
				{
					foreach ($approve as $mac) //Activate the AP in the database
					{
						$mac = $this->security->xss_clean($mac);
						$this->ap_model->activateAP($mac);
					}
				}

				if(!empty($delete))
				{
					foreach ($delete as $mac) //Delete the AP in the database
					{
						$mac = $this->security->xss_clean($mac);
						$this->ap_model->deleteAP($mac);
					}
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

			if (isset($_POST['add'])) //if the add button has been pushed add command
			{
				// get and sanatize variables
				$mac = $this->input->post('mac');
				$mac = $this->security->xss_clean($mac);
				$command = $this->input->post('command');
				$command = $this->security->xss_clean($command);

				$this->config_model->setCommand($mac, $command);
			}

			if (isset($_POST['remove'])) //if the add button has been pushed add command
			{
				// get and sanatize variables
				$mac = $this->input->post('mac');
				$mac = $this->security->xss_clean($mac);

				$this->config_model->removeCommand($mac, $command);
			}
			redirect('manage/editAP/'.$mac);
		}

		function editAP($mac)
		{
			// Edit name, location, notes and group memebership
			$this->load->model('ap_model');
			$this->load->model('group_model');
			$this->load->model('config_model');
			$this->load->model('heartbeat_model');

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

			$menu['page_name'] = "manage";
			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = $this->ap_model->pendingCommand();
			$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

			// Build editAP Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('editAP_view', $data);
			$this->load->view('footer_view');
		}
	}
?>
