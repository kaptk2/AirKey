<?php
// TABLE LAYOUT
//CREATE TABLE apList(id INTEGER PRIMARY KEY, mac varchar(17), key varchar(255), isActive tinyint);
class Register_model extends Model
{
  function validateUser($mac, $key)
  {
    $this->db->where('mac', $mac);
    $this->db->where('key', md5($key));
    $query = $this->db->get('apList');
    if($query->num_rows == 1)
    {
      return true;
    }
  }

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
}
?>
