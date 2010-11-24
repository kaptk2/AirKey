<?php
  class Manage extends Controller
  {
    function getAllAP()
    {
      $this->load->model('manage_model');
      $data['query'] = $this->manage_model->showAllAP();
      $this->load->view('manage_view', $data);
    }
  }
?>
