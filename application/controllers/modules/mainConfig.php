<?php
  class MainConfig extends CI_Controller
  {
    var $mac;
    var $key;

    function index()
    {
      $this->load->view('registerError_view');
    }

    function auth($mac = '', $key = '', $command = '')
    {
      if ($mac && $key)
      {
        $this->load->library('Validate');
        $this->load->helper('file');

        $validUser = $this->validate->validateUser($mac, $key);
          if($validUser)
          {
            // Valid User found make the config file
            $this->load->model('modules/mainconfig_model');
            // If the command was not blank then remove it
            if ($command == 'removeCommand')
            {
              $this->mainconfig_model->removeCommand($mac);
              return true;
            }

            // Find out if a custom version is needed for this mac
            $data = $this->mainconfig_model->getMainConfig($mac);

            $encryptThis = $this->load->view('modules/mainConfig_view', $data, TRUE);

            $password = $this->config->item('networkPassword'); // Get password from config file
            $file = "./static/tmp/$mac";

            if ( ! write_file($file, $encryptThis))
            {
              // File can be written
              echo "opps no file";
              //$this->load->view('registerError_view');
            }
            else
            {
              system("./static/scripts/encode.sh $password $file");
              //DELETE the file
              unlink($file);
            }
          }
          else
            // Not a valid username or password
            echo "invalid username";
            //$this->load->view('registerError_view');
      }
      else
        $this->load->view('registerError_view'); // MAC or Key not passed
    }

    function editDefaults()
    {
      $this->load->model('modules/mainconfig_model');
      $this->load->model('manage_model');

      $groupMembers = $this->manage_model->getGroup('default');
      $groupMembers[] .= 'default';
      $mac = reset($groupMembers); // MAC of first group member
      $data = $this->mainconfig_model->getMainConfig($mac);

      if($_POST)
      {
        foreach($groupMembers as $groupMember)
        {
          $update = array (
          'mac' => $groupMember,
          'currentVersion' => $this->security->xss_clean($this->input->post('currentVersion')),
          'modules' => $this->security->xss_clean($this->input->post('modules')),
          'run' => $this->security->xss_clean($this->input->post('run'))
        );
        $this->mainconfig_model->changeConfig($update);
        }
      }

      $data = $this->mainconfig_model->getMainConfig('default');
      $data['moduleName'] = "mainConfig";
      $data['action'] = "editDefaults";
      $data['applyType'] = "group";
      $data['applyTo'] = "default";
      $this->load->view('modules/mainConfig_config_view', $data);
    }

    function changeConfig()
    {
    $this->load->model('modules/mainconfig_model');

    if ($_POST && $_POST['applyTo'] != NULL) //make sure that data has been posted
      {
        if ($this->input->post('applyType') == "mac")
        {
          $update = array (
            'mac' => $this->input->xss_clean($this->input->post('applyTo')),
            'currentVersion' => $this->input->xss_clean($this->input->post('currentVersion')),
            'modules' => $this->input->xss_clean($this->input->post('modules')),
            'run' => $this->input->xss_clean($this->input->post('run'))
          );
          $this->mainconfig_model->changeConfig($update);
        }
        else
        {
          // Group Selected
          $this->load->model('manage_model');
          $groupName = $this->input->post('applyTo');
          $groupMembers = $this->manage_model->getGroup($groupName);

          foreach($groupMembers as $groupMember)
          {
            $update = array (
            'mac' => $groupMember,
            'currentVersion' => $this->input->xss_clean($this->input->post('currentVersion')),
            'modules' => $this->input->xss_clean($this->input->post('modules')),
            'run' => $this->input->xss_clean($this->input->post('run'))
          );
          $this->mainconfig_model->changeConfig($update);
          }
        }
      }

      $data['action'] = "changeConfig";
      $data['moduleName'] = "mainConfig";
      $this->load->view('modules/mainConfig_apply_view',$data);
      $this->load->view('modules/mainConfig_config_view',$data);
    }

    function getConfig()
    {
      $this->load->model('modules/mainconfig_model');
      if ($_POST && $_POST['applyTo'] != NULL) //make sure that data has been posted
      {
        if ($this->input->post('applyType') == "mac")
        {
          $mac = $this->input->xss_clean($this->input->post('applyTo'));
          $data = $this->mainconfig_model->getMainConfig($mac);
          echo json_encode($data);
        }
        else //Group Selected
        {
          $this->load->model('manage_model');
          $groupName = $this->input->post('applyTo');
          $groupMembers = $this->manage_model->getGroup($groupName);
          $mac = reset($groupMembers); // MAC of first group member
          $data = $this->mainconfig_model->getMainConfig($mac);
          echo json_encode($data);
        }
      }
    }
  }
?>
