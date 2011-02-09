<?php
  class Manage extends Controller
  {
    function index()
    {
      $this->load->view('header_view');

      $this->load->model('manage_model');
      $data['pending'] = $this->manage_model->showPendingAP();
      $data['active'] = $this->manage_model->showActiveAP();
      
      $data['activeAP'] = $this->manage_model->activeAP();
      $data['pendingCmd'] = $this->manage_model->pendingCmd();

      $this->load->model('heartbeat_model');
      $data['heartbeat'] = $this->heartbeat_model->showLog();

      $this->load->view('dashboard_view', $data);
      $this->load->view('footer_view');
    }

    function pendingAP()
    {
      if ($_POST) //make sure that data has been posted
      {
        $this->load->model('manage_model');

        foreach ($this->input->post('approve') as $mac) //Activate the AP in the database
        {
          $mac = $this->input->xss_clean($mac);
          $this->manage_model->approveAP($mac);
        }

        foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
        {
          $mac = $this->input->xss_clean($mac);
          $this->manage_model->deleteAP($mac);
        }
      }
      redirect('manage');
    }

    function activeAP()
    {
      if ($_POST) //make sure that data has been posted
      {
        $this->load->model('manage_model');

        foreach ($this->input->post('deactivate') as $mac) //Deactivate the AP in the database
        {
          $mac = $this->input->xss_clean($mac);
          $this->manage_model->deactivateAP($mac);
        }

        foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
        {
          $mac = $this->input->xss_clean($mac);
          $this->manage_model->deleteAP($mac);
        }
      }
      redirect('manage');
    }
  }
?>
