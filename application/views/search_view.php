<div class='container'>
	<div class='content'>
		<h3>Search Results</h3>
		<p>
			<?php
				if (!empty($ap_results)) // ap_results returns results
				{
					echo "<h4>AP Results</h4>";
					print '<ul>';
					foreach($ap_results as $row)
					{
						if (!empty($row->ap_name)) // Check to see if a friendly name assigned
							$name = $row->ap_name;
						else
							$name = $row->mac;
						// Display the results
						print '<li><a href="'.site_url("manage/editAP/".$row->mac).'">'.$name.'</a></li>';
					}
					print '</ul>';
				}
				else
				{
					print 'No Matching Access Points';
				}
			?>
			<br /><br />
			<?php
				if (!empty($group_results)) // group_results returns results
				{
					echo "<h4>Group Results</h4>";
					print '<ul>';
					foreach($group_results as $row)
					{
						print '<li><a href="'.site_url("group/editGroup/".$row->group_name).'">'.$row->group_name.'</a></li>';
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
