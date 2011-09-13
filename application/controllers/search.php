<?php
	class Search extends CI_Controller
	{
		function index()
		{
			$this->load->model('heartbeat_model');
			$this->load->model('group_model');
			$this->load->model('ap_model');

			if ($_POST) //make sure that data has been posted
			{
				$search_term = $this->input->post('search_term');
				$search_term = $this->security->xss_clean($search_term);
				// Search APs
				$data['ap_results'] = $this->ap_model->apSearch($search_term);
				// Search Groups
				$data['group_results'] = $this->group_model->groupSearch($search_term);

				// Load View to show search results
				$menu['page_name'] = ""; //pass empty page name
				$menu['total_AP'] = $this->ap_model->activeAPCount();
				$menu['pending'] = $this->ap_model->pendingCommand();
				$menu['network_status'] = $this->heartbeat_model->countTroubleAP();
				// Build Search Page
				$this->load->view('header_view');
				$this->load->view('menu_view', $menu);
				$this->load->view('search_view', $data);
				$this->load->view('footer_view');
			}
			else
			{
				// No Search Term Passed Error
				$data['error_msg'] = "No Search Term";
				$this->load->view('error_view', $data);
			}
		}
	}
?>
