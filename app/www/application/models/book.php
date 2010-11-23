<?php

class Book extends Model {
	var $id = "";
	var $title = "";
	var $author = "";

	function __construct($title = "", $author = ""){
		parent::Model();
	}

	function getBooks(){
		$query = $this->db->get('books');
		return $query->result();
	}

	function addBook($title, $author){
		$this->db->insert('books', array('title'=> $title, 'author' => $author));
	}

	function deleteBook($id){
		$this->db->where('id', $id);
		$this->db->delete('books');
	}
}

