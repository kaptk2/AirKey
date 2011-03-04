<?php
	class Fileeditor extends CI_Controller
	{
		function index()
		{
			echo "I am index!";
		}

		function edit($filePath)
		{
			if ($filePath)
			{
				//Edit function allows you to choose from a list of files that you may want
				//to edit. It also allows you to create or delete new files.
					$this->load->helper('directory');

					$data['files'] = directory_map($_SERVER['DOCUMENT_ROOT'] . "/static/modules/$filePath/", TRUE);
					$data['filePath']= $filePath;

					$this->load->view('fileeditor_view', $data);
			}
			else
			{
				$data['error_msg'] = "No module path given";
				$this->load->view('error_view', $data);
			}
		}

		function codeedit($filePath, $fileName)
		{
			$this->load->helper('file');

			if ($_POST)
			{
				$filedata = $this->input->post('fileContents');

				//Attempt to save the file
				if( ! write_file($_SERVER['DOCUMENT_ROOT'] . "/static/modules/$filePath/$fileName", $filedata))
					echo "ERROR WRITING FILE"; // TODO make this tell the view there was an error
				else
					$this->load->view('success_view');
			}
			else
			{
				//Get the current data out of the file
				$data['fileContents'] = read_file($_SERVER['DOCUMENT_ROOT'] . "/static/modules/$filePath/$fileName");
				$this->load->view('codeedit_view', $data);
			}
		}

		function createfile()
		{
			if ($_POST)
			{
				$fileName = $this->security->xss_clean($this->input->post('fileName'));
				$filePath = $this->security->xss_clean($this->input->post('filePath'));
				redirect("/fileeditor/codeedit/$filePath/$fileName");
			}
		}
	}
?>
