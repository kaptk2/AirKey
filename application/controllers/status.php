<?php

	class Status extends CI_Controller
	{
		function index($page_num = 0)
		{
			$this->load->model('ap_model');
			$this->load->model('heartbeat_model');

/*  TODO Add pagination to the last log
			$this->load->library('pagination');
			//Setup pagination
			$per_page = 10;
			$config['base_url'] = site_url('manage/index');
			$config['total_rows'] = $this->ap_model->activeAPCount();
			$config['per_page'] = $per_page;
			$this->pagination->initialize($config);
*/

			$data['issue'] = $this->heartbeat_model->apHealth();
			$data['log'] = $this->heartbeat_model->showLog();
			//$data['pages'] = $this->pagination->create_links();

			$menu['page_name'] = "status";
			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = $this->ap_model->pendingCommand();
			$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

			// Build Dashboard Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('status_view', $data);
			$this->load->view('footer_view');
		}

		function currentIssues()
		{
			$this->load->model('heartbeat_model');

			if ($_POST)
			{
				// If data has been POST'ed process the form
				$actions = $_POST['action'];
				foreach ($actions as $mac => $action)
				{ // Itterate through the array
					switch ($action)
					{
						case 'Nothing':
							// Do nothing leave data untouched
							break;
						case 'Ignore':
						case'Acknowledged':
							$this->heartbeat_model->changeStatus($mac, $action);
							break;
						default:
							// Invalid or unknown value passed; redirect to status page
							redirect('/status', 'refresh');
					}
				}
				//All done processing go back to status page.
				redirect('/status');
			}
			else
			{
				// else send them back to the status page
				redirect('/status', 'refresh');
			}
		}
	}
?>
