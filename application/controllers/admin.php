<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

   public function __construct()
   {
      session_start();
      parent::__construct();
   }

public function index()
   {
      if ( isset($_SESSION['username']) ) {
         redirect('mangage');
      }
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|required');
      $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

      if ($this->form_validation->run()) {
         // then validation passed. See if they are in DB
         $this->load->model('admin_model');
         $res = $this
                  ->admin_model
                  ->verify_user(
                     $this->input->post('email_address'),
                     $this->input->post('password')
                  );

         if ( $res !== false ) {
            $_SESSION['username'] = $this->input->post('email_address');
            redirect('manage');
         }

      }

      $this->load->view('login_view');
   }

   public function logout()
   {
      session_destroy();
      redirect('admin');
   }
}
