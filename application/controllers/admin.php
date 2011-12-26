<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

   public function __construct()
   {
      session_start();
      parent::__construct();
   }

	public function index()
	{
		if ( isset($_SESSION['username']) )
		{
			redirect('mangage');
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|required');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

		if ($this->form_validation->run())
		{
			// then validation passed. See if they are in DB
			$this->load->model('admin_model');
			$res = $this
				->admin_model
				->verify_user(
					$this->input->post('email_address'),
					$this->input->post('password')
				);

			if ( $res !== false )
			{
				$_SESSION['username'] = $this->input->post('email_address');
				redirect('manage');
			}
		}
		$this->load->view('login_view');
	}

	public function manage_users()
	{
		if ( !isset($_SESSION['username']) )
		{
			// A valid user session is required to mangage usere
			redirect('admin');
		}
		// We have a valid user
		$this->load->model('ap_model');
		$this->load->model('heartbeat_model');
		$this->load->model('admin_model');

		//Build menu
		$menu['page_name'] = "users";
		$menu['total_AP'] = $this->ap_model->activeAPCount();
		$menu['pending'] = $this->ap_model->pendingCommand();
		$menu['network_status'] = $this->heartbeat_model->countTroubleAP();

		$data['user'] = $this->admin_model->showUsers();

		// Build User Mangager Page
		$this->load->view('header_view');
		$this->load->view('menu_view', $menu);
		$this->load->view('user_view', $data);
		$this->load->view('footer_view');
	}

	public function logout()
	{
		session_destroy();
		redirect('admin');
	}
}
