<?php

class Mainconfig_model extends CI_Model
{

  function getMainConfig($mac)
  {
    $query =  $this->db->get_where('config',array('mac'=>$mac));

    if($query->num_rows == 0)
    {
      //Return the default config
      $query =  $this->db->get_where('config',array('mac'=>'default'));
    }

    $data = $query->row_array();
    return $data;
  }

  function installModule($data)
  {
    $data = array(
      'mac' => 'default',
      'currentVersion' => '0',
      'modules' => ''
    );
    $this->db->insert('config', $data);
  }

  function deleteModule($data)
  {
    $this->db->delete('config', $data);
  }

  function changeConfig($update)
  {
    $query =  $this->db->get_where('config',array('mac'=>$update['mac']));

    if($query->num_rows == 0) // If the data does not exist
      $this->db->insert('config', $update); // Insert it
    else // else update it
    {
      $this->db->where('mac', $update['mac']);
      $this->db->update('config', $update);
    }
  }

  function removeCommand($mac)
  {
    $this->db->where('mac', $mac);
    $this->db->set('run', ' ');
    $this->db->update('config');
  }

  function deleteConfig($update)
  {
    $this->db->delete('config', $update);
  }
}
?>
