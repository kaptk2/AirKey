<?php

	class Status extends CI_Controller
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

			$menu['page_name'] = "status";
			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = $this->ap_model->pendingCommand();
			$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

			// Build Dashboard Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('dashboard_view', $data);
			$this->load->view('footer_view');
		}
	}
?>
