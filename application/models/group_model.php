<?php
class group_model extends CI_Model
{

	function showGroups()
	{
		$query = $this->db->get('groups');
		return $query->result();
	}

	function getGroup($mac)
	{
		$query = $this->db->get_where('associates', array('mac' => $mac));
		return $query->row(); //return the group the ap is in
	}

	function deleteGroup($group_name)
	{
		// delete the group from the database
		if ($group_name != 'default') //Default group not allowed to be removed
		{
			$this->db->delete('groups', array('group_name' => $group_name));
		}
	}

	function createGroup($group_name, $group_desc)
	{
		$newGroupInsert = array(
			'group_name' => $group_name,
			'group_description' => $group_desc
		);

		$groupInsert = $this->db->insert('groups', $newGroupInsert);
		return $groupInsert;
	}

	function updateGroupDesc($group_name, $group_desc)
	{
		// function that updates the group description
		$newGroupUpdate = array(
			'group_description' => $group_desc
		);

		$this->db->where('group_name', $group_name);
		$groupUpdate = $this->db->update('groups', $newGroupUpdate);
		return $groupUpdate;
	}

	function moveGroupMembers($old_group, $new_group)
	{
		$this->db->where('group_name', $old_group);
		$moved = $this->db->update('associates', array('group_name' => $new_group));
		return $moved;
	}

	function updateGroupMember($mac, $group_name)
	{
		// function that adds an AP to a new group
		$updateMemberInsert = array(
			'group_name' => $group_name
		);

		$this->db->where('mac', $mac);
		$memberUpdate = $this->db->update('associates', $updateMemberInsert);
		return $memberUpdate;
	}

	function getGroupDescription($group_name)
	{
		$query = $this->db->get_where('groups', array('group_name' => $group_name));
		return $query->row();
	}
}
?>
