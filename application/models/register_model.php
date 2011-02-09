<?php

class Register_model extends Model
{

  function validateMAC($mac)
  {
    $this->db->where('mac', $mac);
    $query = $this->db->get('apList');
    if($query->num_rows == 1)
    {
      return true;
    }
  }

  function insertAP($mac, $key)
  {
    $newAPInsert = array(
      'mac' => $mac,
      'key' => md5($key),
      'isActive' => '0'
    );
    $insert = $this->db->insert('apList', $newAPInsert);
    return $insert;
  }

  function getVersion($mac)
  {
    $this->db->select('currentVersion');

    $query = $this->db->get_where('config',array('mac' => $mac));

    if($query->num_rows == 1)
    {
      $row = $query->row();
      return $row->currentVersion;
    }
    $this->db->select('currentVersion');
    $query = $this->db->get_where('config',array('mac' => 'default'));
    $row = $query->row();
    return $row->currentVersion;
  }
}
?>
