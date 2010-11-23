<?php

class Books extends Controller {
	function __construct(){
		parent::Controller();
		$this->load->model("Book");
	}

	function index(){
		$data['books'] = $this->Book->getBooks();
		
		$this->load->view('books/index', $data);
	}

	function json(){
		$data['books'] = $this->Book->getBooks();
		
		$this->load->view('books/json', $data);
	}

	function add(){
		$title = $this->input->post('title');
		$author = $this->input->post('author');
		$this->Book->addBook($title, $author);

		$this->load->helper('url');
		redirect('/books/');
	}

	function delete($id){
		$this->Book->deleteBook($id);
		$this->load->helper('url');
		redirect(array('books'));
	}

	function backup(){
		$this->load->dbutil();
		$backup = $this->dbutil->backup(array('format' => 'txt'));
		var_dump($backup);
	}
}

