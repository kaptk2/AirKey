<div class='container'>
	<div class='content'>
		<h3>Search Results</h3>
		<p>
			<?php
				if (is_array($ap_results)) // ap_results returns results
				{
					print '<ul>';
				
					foreach($ap_results as $row)
					{
						if (!empty($row->ap_name)) // Check to see if a friendly name assigned
							$name = $row->ap_name;
						else
							$name = $row->mac;
						// Display the results
						print '<li><a href="'.site_url("manage/editAP/".$row->mac).'">'.$name.'</li>';
					}
					print '</ul>';
				}
				else
				{
					print 'No Matching Access Points';
				}
			?>
		</p>
		<p>
			<?php
				if (is_array($group_results)) // group_results returns results
				{
					print '<ul>';
					foreach($group_results as $row)
					{
						print '<li><a href="'.site_url("group/editGroup/".$row->group_name).'">'.$row->group_name.'</li>';
					}
					print '</ul>';
				}
				else
				{
					print 'No Matching Groups';
				}
			?>
		</p>
	</div>
</div>
