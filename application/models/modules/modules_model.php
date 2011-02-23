<?php

class Modules_model extends CI_Model
{
  function getConfig($data)
  {
    $query =  $this->db->get_where('moduleList',array('mac'=>$data['mac'], 'moduleName'=>$data['moduleName']));

    if($query->num_rows == 0)
    {
      //Return the default config
      $query =  $this->db->get_where('moduleList',array('mac'=>"default", 'moduleName'=>$data['moduleName']));
    }

    foreach ($query->result() as $row)
    {
      $data = array(
        'moduleName' => $row->moduleName,
        'packageName' => $row->packageName,
        'remoteFile' => $row->remoteFile,
        'localFile' => $row->localFile,
        'command' => $row->command
      );
    }
    return $data;
  }

  function installModule($data)
  {
    $query =  $this->db->get_where('moduleList',array('mac'=>$data['mac'], 'moduleName'=>$data['moduleName']));
    if($query->num_rows == 0)
    {
      $this->db->insert('moduleList', $data); // Make sure that default data does not already exist
    }
  }

  function deleteModule($data)
  {
    $this->db->delete('moduleList', $data);
  }

  function changeConfig($update)
  {
    $query =  $this->db->get_where('apList',array('mac'=>$update['mac']));

    if($query->num_rows > 0 || $update['mac'] == 'default') // AP exists in apList or is the default entry
    {
      $query =  $this->db->get_where('moduleList',array('mac'=>$update['mac'], 'moduleName'=>$update['moduleName']));

      if($query->num_rows == 0) // If the data does not exist
        $this->db->insert('moduleList', $update); // Insert it
      else // else update it
      {
        $this->db->where('moduleName', $update['moduleName']);
        $this->db->where('mac', $update['mac']);
        $this->db->update('moduleList', $update);
      }

      // Update Version Number
      $query =  $this->db->get_where('config',array('mac'=>$update['mac'])); // Find what the current version is

      if($query->num_rows == 0) // If the data does not exist
      {
        $query =  $this->db->get_where('config',array('mac'=>'default'));
        $row = $query->row();
        $newVersion = $row->currentVersion + 1; // update version number
        $this->db->insert('config', array('mac'=>$update['mac'], 'currentVersion'=>$newVersion, 'modules'=>$row->modules)); // Insert it
      }
      else // else update it
      {
        $row = $query->row();
        $newVersion = $row->currentVersion + 1; // update version number

        $data = array('currentVersion' => $newVersion);
        $this->db->where('mac', $update['mac']);
        $this->db->update('config', $data);
      }
    }
    else
      return false;
    return true; //Everything worked return true
  }

  function deleteConfig($update)
  {
    $this->db->where('moduleName', $update['moduleName']);
    $this->db->where('mac', $update['mac']);
    $this->db->update('moduleList', $update);
  }

  function removeGroup($mac)
  {
    $this->db->where('mac', $mac);
    $data = array('groupName' => '');
    $this->db->update('apList', $data);
  }
}
?>
