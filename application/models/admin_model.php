<?php

class Admin_model extends CI_Model {

	public function verify_user($email, $password)
	{
		$query = $this
			->db
			->where('email', $email)
			->where('password', sha1($password))
			->limit(1)
			->get('users');

		if ( $query->num_rows == 1 ) {
			// a valid account was found
			return $query->row();
		}
		return false;
	}

	public function showUsers()
	{
		$query = $this->db->get('users');
		return $query->result();
	}

	public function addUser($fn, $ln, $email, $password)
	{
		$data = array(
			'first_name' => $fn,
			'last_name' => $ln,
			'email' => $email,
			'password' => sha1($password)
		);
		$this->db->insert('users', $data);

		return true;
	}

	public function deleteUser($id)
	{
		$this->db->delete('users', array('id' => $id));
		return true;
	}

	public function changePass($id, $password)
	{
		$this->db->where('id', $id);
		$this->db->update('users', array('password' => sha1($password)));
		return true;
	}

	public function modifyUsers($id, $fn, $ln, $email)
	{
		$data = array(
			'first_name' => $fn,
			'last_name' => $ln,
			'email' => $email
		);
		$this->db->where('id', $id);
		$this->db->update('users', $data);

		return true;
	}
}
?>
