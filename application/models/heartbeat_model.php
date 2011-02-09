<?php

class Heartbeat_model extends Model
{

  function heartbeat($data)
  {
    $query = $this->db->get_where('heartbeat',array('mac' => $data['mac']));

    if($query->num_rows == 1)
    {
      $this->db->where('mac', $data['mac']);
      $this->db->update('heartbeat', $data);
    }
    else
    {
      $this->db->insert('heartbeat', $data);
    }
  }

  function showLog()
  {
    $this->db->order_by("tStamp", "desc");
    $showLog =  $this->db->get('heartbeat', 5);
    return $showLog->result();
  }
}
?>
