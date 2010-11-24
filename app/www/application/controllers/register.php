<?php

class Register extends Controller
{

  var $mac;
  var $key;

  function Register()
  {
    parent::Controller();
  }

  function index()
  {
    $this->load->view('registerError_view');
  }

  function auth($mac = '', $key = '')
  {
    if ($mac && $key)
    {
      $data['mac'] = $mac;
      $data['key'] = $key;

      //Get AP Credentials from database
      $this->load->model('register_model');
      $validMAC = $this->register_model->validateMAC($mac);
      $validUser = $this->register_model->validateUser($mac, $key);

      if($validMAC) //Check to make sure something is returned
      {
        if($validUser)
        {
          $this->load->view('you_view', $data);
        }
        else
        {
          // Invalid Credinatls passed
          $this->load->view('registerError_view');
        }
      }
      else
      {
        //The AP is not in database add it to pending table
        $addAP = $this->register_model->insertAP($mac, $key);
        if ($addAP)
        {
          $this->load->view('add_view', $data);
        }
        else
        {
          $this->load->view('registerError_view');
        }
      }
    }
    else
    {
      // MAC and Key were not passed error out
      $this->load->view('registerError_view');
    }
  }
}
?>
