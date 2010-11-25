<?php
  class Manage extends Controller
  {
    function index()
    {
      $this->load->view('header_view');
      $this->load->model('manage_model');
      $data['query'] = $this->manage_model->showPendingAP();
      $this->load->view('dashboard_view', $data);
      $this->load->view('footer_view');
    }

    function activateAP()
    {
      //Activate the AP in the database
      $mac = $this->input->post('mac');

      $activateAP = array('isActive' => '1');

      $this->db->where('mac', $mac);
      $this->db->update('apList', $activateAP);

      $message = "AP with MAC: " . $mac . " has been activated";
      $array = array('result' => $message);
      echo json_encode($array);
    }
  }
?>
