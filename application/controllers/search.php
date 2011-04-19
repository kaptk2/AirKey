<?php
	class Search extends CI_Controller
	{
		function index()
		{
			$menu['pageName'] = "Search";
			$this->load->view('header_view');
			$this->load->view('menu_view');
			$this->load->model('ap_model');
			$this->load->model('group_model');
			$this->load->view('footer_view');	
		}

		function search_for_ap($input)
		{
			if($input != "")
			{
				$this->load->model('ap_model');

				$ap_results = array();
				$macs = $this->db->get_where('ap', array('mac' => $mac));
				
				foreach($macs as $cursor)
				{
					if($cursor == $input)
						   array_push($ap_results, $cursor);
				}

				return $ap_results;				
			}

			else
			{			
				echo "Search input was empty.";
				exit;
			}			
		}

		function search_for_group($input)
		{
			if($input != "")
			{
				$this->load->model('group_model');

				$group_results = array();
				$group_names = $this->db->get_where('groups', array('group_name' => $group_name));
				
				foreach($group_names as $cursor)
				{
					if($cursor == $input)
						   array_push($group_results, $cursor);
				}

				return $group_results;
			}

			else
			{
				echo "Search input was empty.";
				exit;
			}
		}
	}
?>
