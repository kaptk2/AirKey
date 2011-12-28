<div class='container'>
	<div id='mainContent'>
		<a href="addUser">Add a user</a>
		<br />
		<br />
		<strong>Existing Users:</strong>
		<form method="post" action="modifyUsers">
			<table border='1'>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email Address</th>
					<th>Delete?</th>
					<th>Change Password?</th>
				</tr>
				<?php
				// Build Up Users Table
				$odd = true;
				foreach($user as $row)
				{
					print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
					print '<td><input type=hidden name="'.$row->id.'[id]" value="'.$row->id.'" />';
					print '<input name="'.$row->id.'[fn]" value="'.$row->first_name.'" /></td>';
					print '<td><input name="'.$row->id.'[ln]" value="'.$row->last_name.'" /></td>';
					print '<td><input name="'.$row->id.'[email]" value="'.$row->email.'" /></td>';
					print '<td><a href="deleteUser/'.$row->id.'">Delete?</a></td>';
					print '<td><a href="changePass/'.$row->id.'">Change Password?</a></td>';
					print '</tr>';
				}
				?>
			</table>
		<input type="submit" value="Modify User(s)" />
		</form>
	</div>
</div>
