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
}
?>
