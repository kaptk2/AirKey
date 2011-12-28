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
			redirect('manage');
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

	public function manageUsers()
	{
		if (!isset($_SESSION['username']))
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

	public function addUser()
	{
		$this->load->library('form_validation');
		// If something has been posted and a valid session exists
		if ($_POST && isset($_SESSION['username']))
		{
			// validate form data
			$this->form_validation->set_rules('fn', 'First Name', 'required');
			$this->form_validation->set_rules('ln', 'Last Name', 'required');
			$this->form_validation->set_rules('email', 'Email Address', 'valid_email|required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

			if ($this->form_validation->run())
			{
				$this->load->model('admin_model');
				// insert into database
				$this->admin_model->addUser(
					$this->input->post('fn'),
					$this->input->post('ln'),
					$this->input->post('email'),
					$this->input->post('password')
				);
				redirect('admin/manageUsers');
				//print_r($_POST); //DEBUG
			}
		}
		// Else just load the view
		$this->load->view('addUser_view');
	}

	public function deleteUser($id)
	{
		// If an id and a valid session exists
		if ($id && isset($_SESSION['username']))
		{
			// if the ID is the default admin user display error and die
			if ($id == 1)
			{
				$data['error_msg'] = "Can not delete the default admin account<br />";
				$data['error_msg'] .= "You can modify the account but not delete it";
				$this->load->view('error_view', $data);
			}
			else // not the admin user so do the delete
			{
				$this->load->model('admin_model');
				$this->admin_model->deleteUser($id);
				redirect('admin/manageUsers');
				//echo "The ID is: $id"; //DEBUG
			}
		}
		else // no valid session or $id was not set
		{
			redirect('manage'); // go to the home screen
		}
	}

	public function changePass($id)
	{
		$this->load->library('form_validation');
		// See if session exists
		if (!isset($_SESSION['username']))
		{
			// Session not set redirct to main page
			redirect('manage');
		}

		$this->load->library('form_validation');
		if ($_POST)
		{
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

			if ($this->form_validation->run())
			{
				$this->load->model('admin_model');
				// change the password
				$this->admin_model->changePass(
					$id,
					$this->input->post('password'));
				redirect('admin/manageUsers');
			}
		}
		// Load the change password view
		$data['id'] = $id;
		$this->load->view('pw_change_view', $data);
	}

	public function modifyUsers()
	{
		// See if session exists
		if (!isset($_SESSION['username']))
		{
			// Session not set redirct to main page
			redirect('manage');
		}

		if ($_POST)
		{
			$this->load->model('admin_model');
			foreach ($_POST as $value)
			{
				$this->admin_model->modifyUsers(
					$value['id'],
					$value['fn'],
					$value['ln'],
					$value['email']);
			}
			redirect('admin/manageUsers');
		}
	}

	public function logout()
	{
		session_destroy();
		redirect('admin');
	}
}
