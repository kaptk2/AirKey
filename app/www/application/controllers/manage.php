<?php
  class Manage extends Controller
  {
    function index()
    {
      $this->load->model('manage_model');
      $data['query'] = $this->manage_model->showAllAP();
      $this->load->view('header_view');
      $this->load->view('dashboard_view', $data);
      $this->load->view('footer_view');
    }
  }
?>
