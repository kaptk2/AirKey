<?php
class Manage_model extends CI_Model
{

  function activeAP()
  {
    $this->db->get_where('apList',array('isActive'=>1));
    $data = $this->db->count_all_results();
    return $data;
  }
  
  function pendingCmd()
  {
    //$this->db->where('run IS NOT NULL');
    //$query = $this->db->get('config');
    //$this->db->get_where('config', array('run IS NOT' => 'NULL'));
    $this->db->query('SELECT run FROM config WHERE run IS NOT NULL');
    $data = $this->db->count_all_results();
    return $data;
  }
  
  function apHealth()
  {
    //TODO
    return true;
  }

  function showPendingAP()
  {
    $pending =  $this->db->get_where('apList',array('isActive'=>0));
    return $pending->result();
  }

  function showActiveAP()
  {
    $query =  $this->db->get_where('apList',array('isActive'=>1));
    $data = $query->result();
    return $data;
  }

  function showAP($mac)
  {
    $query =  $this->db->get_where('apList',array('mac'=>$mac,'isActive'=>1));
    $data = $query->row();
    return $data;

  }

  function approveAP($mac)
  {
    $this->db->where('mac', $mac);
    $this->db->set('isActive', '1');
    $this->db->update('apList');
  }

  function deactivateAP($mac)
  {
    $this->db->where('mac', $mac);
    $this->db->set('isActive', '0');
    $this->db->update('apList');
  }

  function deleteAP($mac)
  {
    $this->db->where('mac', $mac);
    $this->db->delete('apList');
  }

  function getGroup($groupName)
  {
    $this->db->select('mac');
    $query = $this->db->get_where('apList',array('groupName' => $groupName));

    if ($query->num_rows() > 0)
    {
      foreach ($query->result() as $row)
      {
        $data[] = $row->mac;
      }
      return $data;
    }
    return false;
  }

  function changeGroup($mac, $groupName)
  {
    $query =  $this->db->get_where('apList',array('mac'=>$mac));

    if($query->num_rows == 1) // Make sure non existant MAC's don't get put in a group
    {
      $this->db->where('mac', $mac);
      $this->db->set('groupName', $groupName);
      $this->db->update('apList');
    }
  }

  function changeName($mac, $name)
  {
    $query =  $this->db->get_where('apList',array('mac'=>$mac, 'isActive'=>'1'));

    if($query->num_rows == 1) // Make sure AP exists and is active
    {
      $this->db->where('mac', $mac);
      $this->db->set('name', $name);
      $this->db->update('apList');
    }
  }
}
?>
