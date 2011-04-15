<?php
	class ap_model extends CI_Model
	{

		function validateMAC($mac)
		{
			$this->db->where('mac', $mac);
			$query = $this->db->get('ap');
			if($query->num_rows == 1)
			{
				return true;
			}
		}

		function insertAP($mac, $key)
		{
			$newAPInsert = array(
				'mac' => $mac,
				'ap_key' => md5($key),
				'is_active' => '0'
			);

			$apInsert = $this->db->insert('ap', $newAPInsert);
			return $apInsert;
		}

		function showPendingAP()
		{
			// Show all AP's who have not been approved yet
			$pending = $this->db->get_where('ap',array('is_active'=>0));
			return $pending->result();
		}

		function activateAP($mac)
		{
			// set the is_active to true and insert data to support AP
			$newGroupInsert = array(
				'mac' => $mac,
				'group_name' => 'default'
			);
			$newConfigInsert = array(
				'mac' => $mac,
				'current_version' => '1'
			);
			$newHeartbeatInsert = array(
				'mac' => $mac,
				'time_stamp' => 'NEW'
			);

			$activateMAC = $this->db->update('ap', array('is_active' => 1), array('mac' => $mac));
			$groupInsert = $this->db->insert('associates', $newGroupInsert);
			$configInsert = $this->db->insert('configuration', $newConfigInsert);
			$heartbeatInsert = $this->db->insert('heartbeat', $newHeartbeatInsert);
		}

		function deleteAP($mac)
		{
			// delete the ap from the database
			$this->db->delete('ap', array('mac' => $mac));
		}

		function showActiveAP($num, $offset)
		{
			// Show all AP's who are active
			$this->db->select('*');
			$this->db->where('is_active', '1');
			$this->db->join('associates', 'associates.mac = ap.mac');
			$this->db->join('heartbeat', 'heartbeat.mac = ap.mac');
			$active = $this->db->get('ap', $num, $offset);
			return $active->result();
		}

		function getName($mac)
		{
			// Get the friendly name of the AP
			$this->db->select('ap_name');
			$query = $this->db->get_where('ap', array('mac' => $mac));
			if ($query->num_rows() > 0)
				$ret = $query->row();
			return $ret->ap_name; //return only the ap name
		}

		function setName($mac, $ap_name)
		{
			// set the friendly name of the AP
			$updateAPname = array(
				'ap_name' => $ap_name
			);
			$this->db->where('mac', $mac);
			$apUpdate = $this->db->update('ap', $updateAPname);
			return $apUpdate;
		}

		function getNotes($mac)
		{
			// Get the notes associated with the AP
			$this->db->select('notes');
			$query = $this->db->get_where('ap', array('mac' => $mac));
			if ($query->num_rows() > 0)
				$ret = $query->row();
			return $ret->notes; //return only the notes associated with ap
		}

		function setNotes($mac, $notes)
		{
			// Set the notes associated with the AP
			$updateAPnotes = array(
				'notes' => $notes
			);
			$this->db->where('mac', $mac);
			$apUpdate = $this->db->update('ap', $updateAPnotes);
			return $apUpdate;
		}

		function getLocation($mac)
		{
			// Get the physical location of the AP
			$this->db->select('mac, location');
			$query = $this->db->get_where('ap', array('mac' => $mac));
			if ($query->num_rows() > 0)
				$ret = $query->row();
			return $ret->location; //return only the location associated with ap
		}

		function setLocation($mac, $location)
		{
			// Set the physical location of the AP
			$updateAPlocation = array(
				'location' => $location
			);
			$this->db->where('mac', $mac);
			$apUpdate = $this->db->update('ap', $updateAPlocation);
			return $apUpdate;
		}

		function activeAPCount()
		{
			// Show all AP's who are active
			$this->db->select('*');
			$this->db->where('is_active', '1');
			$this->db->from('ap');
			$this->db->join('associates', 'associates.mac = ap.mac');
			$this->db->join('heartbeat', 'heartbeat.mac = ap.mac');
			return $this->db->count_all_results();
		}
	}
?>
