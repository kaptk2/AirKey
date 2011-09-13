<?php

class Heartbeat_model extends CI_Model
{

	function heartbeat($data)
	{
		$query = $this->db->get_where('heartbeat',array('mac' => $data['mac']));

		if($query->num_rows == 1) // see if MAC is already in table
		{
			$data['alarm_status'] = 'NULL'; //set alarm status to null becuase it checked in

			$this->db->where('mac', $data['mac']);
			$this->db->update('heartbeat', $data);
		}
		else // MAC not in table add it
		{
			$this->db->insert('heartbeat', $data);
		}
	}

		function apHealth()
		{
			//Select all the AP's whose alarm_status is not null
			$this->db->select('heartbeat.*, ap.ap_name');
			$this->db->where('alarm_status !=', 'NULL');
			$this->db->from('heartbeat');
			// Join that with the AP table and return the result
			$this->db->join('ap', 'heartbeat.mac = ap.mac');
			$query = $this->db->get();

			return $query->result();
		}

		function countTroubleAP()
		{
			/*
			* Return the number of AP's that have not contacted the server in
			* the past 2 minutes and set the alarm_status of those AP's to
			* "Alarm"
			*/
			$time_stamp = strtotime("2 minutes ago");

			/* Select the MAC's of the AP's that have not contacted the server
			// in the past 2mins and have not been ignored */
			$this->db->select('mac, alarm_status');
			$this->db->where('time_stamp <', $time_stamp);
			$this->db->where('time_stamp !=', 'NEW');
			$this->db->where_not_in('alarm_status', array('Ignore'));

			$query = $this->db->get('heartbeat');
			$num_results = $query->num_rows();

			switch ($num_results)
			{
				case 0:
					$status = 'No Issues';
					break;
				case 1:
					$status = $num_results.' issue';
					break;
				default:
					$status = $num_results.' issues';
			}

			if ($num_results > 0)
			{
				foreach ($query->result() as $row)
				{
					if ($row->alarm_status != "Acknowledged")
					{
						// If the alarm has not been acknowledged then set alarm
						$this->db->where('mac', $row->mac);
						$this->db->update('heartbeat', array('alarm_status' => 'Alarm'));
					}
				}
			}
			return $status;
		}

	function changeStatus($mac, $status)
	{
		$data = array('alarm_status' => $status);
		// Update the heartbeat table with $data WHERE mac = $mac
		$this->db->where('mac', $mac);
		$this->db->update('heartbeat', $data);
		return true;
	}

	function emailStatus()
	{
		// Gets the data to be emailed in case of network trouble.
		$time_stamp = strtotime("2 minutes ago");

		/* Select the MAC's of the AP's that have not contacted the server
		// in the past 2mins and have not been ignored */
		$this->db->select('mac');
		$this->db->where('time_stamp <', $time_stamp);
		$this->db->where('time_stamp !=', 'NEW');
		$this->db->where_not_in('alarm_status', array('Ignore', 'Acknowledged'));
		$query = $this->db->get('heartbeat');

		return $query->num_rows();
	}

	function showLog()
	{
		$this->db->order_by("time_stamp", "desc");
		$showLog = $this->db->get('heartbeat', 5);
		return $showLog->result();
	}
}
?>
