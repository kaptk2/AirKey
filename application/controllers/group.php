<?php
	class Group extends CI_Controller
	{
		function index()
		{
			$menu['totalAP'] = "44";
			$menu['pending'] = "1";
			$menu['pageName'] = "group";

			// Build Dashboard Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			//$this->load->view('dashboard_view', $data);
			$this->load->view('footer_view');
		}
	}
?>
