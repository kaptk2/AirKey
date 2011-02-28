<?php
	class Fileeditor extends CI_Controller
	{
		function index()
		{
			echo "I am index!";
		}

		function edit($filePath)
		{
			echo "I am edit!!!<br />";
			echo "I am editing $filePath";
		}
	}
?>
