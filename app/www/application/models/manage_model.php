<?php
// TABLE LAYOUT
//CREATE TABLE apList(id int, mac varchar(17), key varchar(255), isActive tinyint);
class Manage_model extends Model
{
  function showAllAP()
  {
    $query =  $this->db->get('apList');
    return $query->result();
  }
}
?>
