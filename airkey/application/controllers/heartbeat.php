<?php
  class HeartBeat extends Controller
  {
    var $mac;
    var $key;

    function HeartBeat()
    {
      parent::Controller();
    }

    function index()
    {
      $this->load->view('registerError_view');
    }

    function process()
    {
      $mac= $this->input->post('mac');
      $key = $this->input->post('key');

      if ($mac && $key)
      {
        $this->load->library('Validate');
        $validUser = $this->validate->validateUser($mac, $key);
          if($validUser)
          {
            f

            return true;
          }
      }
      $this->load->view('registerError_view');
    }

  }
?>
