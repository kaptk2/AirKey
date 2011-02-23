<?php
  class Manage extends CI_Controller
  {
    function index()
    {
      $this->load->view('header_view');

      $this->load->model('apList_model');
      $data['pending'] = $this->apList_model->showPendingAP();
      $data['active'] = $this->apList_model->showActiveAP();
      $data['dangers'] = $this->apList_model->apHealth();
      
      $data['activeAP'] = $this->apList_model->activeAP();
      $data['pendingCmd'] = $this->apList_model->pendingCmd();

      $this->load->model('heartbeat_model');
      $data['heartbeat'] = $this->heartbeat_model->showLog();

      $this->load->view('dashboard_view', $data);
      $this->load->view('footer_view');
    }

    function pendingAP()
    {
      if ($_POST) //make sure that data has been posted
      {
        $this->load->model('apList_model');

        foreach ($this->input->post('approve') as $mac) //Activate the AP in the database
        {
          $mac = $this->security->xss_clean($mac);
          $this->apList_model->approveAP($mac);
        }

        foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
        {
          $mac = $this->security->xss_clean($mac);
          $this->apList_model->deleteAP($mac);
        }
      }
      redirect('manage');
    }

    function activeAP()
    {
      if ($_POST) //make sure that data has been posted
      {
        $this->load->model('apList_model');

        foreach ($this->input->post('deactivate') as $mac) //Deactivate the AP in the database
        {
          $mac = $this->security->xss_clean($mac);
          $this->apList_model->deactivateAP($mac);
        }

        foreach ($this->input->post('delete') as $mac) //Delete the AP in the database
        {
          $mac = $this->security->xss_clean($mac);
          $this->apList_model->deleteAP($mac);
        }
      }
      redirect('manage');
    }
  }
?>
