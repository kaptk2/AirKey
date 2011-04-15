<?php
	class Group extends CI_Controller
	{
		function index()
		{
			$this->load->model('group_model');
			$this->load->model('ap_model');

			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = "1";
			$menu['page_name'] = "group";
			$menu['network_status'] = "A OK"; //TODO

			$data['current_groups'] = $this->group_model->showGroups();

			// Build Groups Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('group_view', $data);
			$this->load->view('footer_view');
		}

		function editGroup($group_name)
		{
			$this->load->model('modules_model');
			$this->load->model('group_model');
			$this->load->model('ap_model');

			$menu['total_AP'] = $this->ap_model->activeAPCount();
			$menu['pending'] = "1";
			$menu['page_name'] = "group";
			$menu['network_status'] = "A OK"; //TODO

			$data['group_name'] = $group_name;
			$data['all_modules'] = $this->modules_model->showModules();
			$data['installed_modules'] = $this->modules_model->getModules($group_name);
			$data['group_desc'] = $this->group_model->getGroupDescription($group_name);

			// Build Groups Page
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('editGroup_view', $data);
			$this->load->view('footer_view');
		}

		function addGroup()
		{
			// function to add new groups
			$this->load->model('group_model');
			if ($_POST) //make sure that data has been posted
			{
				$this->load->model('group_model');

				$group_name = $this->input->post('group_name');
				$group_name = $this->security->xss_clean($group_name);

				$group_desc = $this->input->post('group_desc');
				$group_desc = $this->security->xss_clean($group_desc);

				$this->group_model->createGroup($group_name, $group_desc);
			}
			redirect('group');
		}

		function removeGroup()
		{
			// function that deletes groups
			$this->load->model('group_model');
			if ($_POST) //make sure that data has been posted
			{
				$this->load->model('group_model');

				foreach ($this->input->post('delete') as $group_name) //Delete the AP in the database
				{
					$group_name = $this->security->xss_clean($group_name);
					$this->group_model->moveGroupMembers($group_name, 'default'); // Move all deleted AP's to default group
					$this->group_model->deleteGroup($group_name);
				}
			}
			redirect('group');
		}

		function updateGroup()
		{
			// function that updates the group descrtiption or name
			$this->load->model('group_model');
			if ($_POST) //make sure that data has been posted
			{
				// Initalize and sanatize variables
				$current_group = $this->input->post('current_group');
				$current_group = $this->security->xss_clean($current_group);
				$new_group = $this->input->post('new_group');
				$new_group = $this->security->xss_clean($new_group);
				$group_desc = $this->input->post('group_desc');
				$group_desc = $this->security->xss_clean($group_desc);
				$moveMembers = $this->input->post('moveMembers');

				if ($new_group === '')
				{
					// Group Name has not be updated
					$this->group_model->UpdateGroupDesc($current_group, $group_desc);
				}
				else // The group name has been updated
				{
					if ($moveMembers == 'true') //should we move the existing members
					{
						$this->group_model->createGroup($new_group, $group_desc);
						$this->group_model->moveGroupMembers($current_group, $new_group);
						$this->group_model->deleteGroup($current_group);
					}
					else
					{
						// move the current memebers into the default group
						$this->group_model->moveGroupMembers($current_group, 'default');
						$this->group_model->createGroup($new_group, $group_desc);
						$this->group_model->deleteGroup($current_group);
					}
				}
				redirect('group');
			}
		}

		function editModules()
		{
			// function that add and removes modules from groups
			$this->load->model('modules_model');
			if ($_POST) //make sure that data has been posted
			{
				// Initalize and sanatize variables
				$group_name = $this->input->post('group_name');
				$group_name = $this->security->xss_clean($group_name);

				$remove_modules = $this->input->post('installed_modules');
				$add_modules = $this->input->post('new_modules');

				// Remove Selected Modules
				if (!empty($remove_modules))
				{
					foreach ($remove_modules as $remove_module)
					{
						$remove_module = $this->security->xss_clean($remove_module);
						$this->modules_model->removeModule($group_name, $remove_module);
					}
				}

				// Add Selected Modules
				if (!empty($add_modules))
				{
					foreach ($add_modules as $add_module)
					{
						$add_module = $this->security->xss_clean($add_module);
						$this->modules_model->addModule($group_name, $add_module);
					}
				}
				redirect('group/editGroup/'.$group_name);
			}
		}
	}
?>
