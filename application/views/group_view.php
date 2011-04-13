<?php $this->load->helper('form'); ?>
<div class='container'>
	<div id='contentLeft'>
		<?php
			$attributes = array('id' => 'editAP', 'class' => 'inline');
			echo form_open('group/addGroup', $attributes);
		?>
			<fieldset>
				<legend>Add Group</legend>
				<label for="group_name">Name: </label>
				<input type="text" name="group_name">
				<br>
				<label for="group_desc">Group Description: </label><br>
				<textarea name="group_desc"></textarea>
				<p><input type="submit" value="submit"></p>
			</fieldset>
		</form>
	</div>
	<div id='contentRight'>
		<?php
			$attributes = array('id' => 'updateGroup', 'class' => 'inline');
			echo form_open('group/updateGroup', $attributes);
		?>
			<fieldset>
				<legend>Modify Group</legend>
				<label for="current_group">Edit Group: </label>
				<select name="current_group">
					<?php
					foreach($currentGroups as $group)
					{
						if ($group->group_name != 'default')
							print '<option value="'.$group->group_name.'">'.$group->group_name.'</option>';
					}
					?>
				</select>
				<br>
				<label for="new_group">New Group Name: </label>
				<input type="text" name="new_group">
				<label for="moveMembers">Move Members? </label>
				<input type="checkbox" name="moveMembers" value="true">
				<br>
				<label for="group_desc">Group Description: </label><br>
				<textarea name="group_desc"></textarea>
				<p><input type="submit" value="submit"></p>
			</fieldset>
		</form>
	</div>
	<div class='content'>
		<h3>Current Groups</h3>
		<?php echo form_open('group/removeGroup'); ?>
			<table>
				<tr>
					<th>Group Name</th><th>Description</th><th>Delete?</th>
				</tr>
				<?php
					$odd = true;
					foreach($currentGroups as $row)
					{
						print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
						print '<td>'.$row->group_name.'</td>';
						print '<td>'.$row->group_description.'</td>';
						if ($row->group_name != 'default')
							print '<td><input type="checkbox" name="delete[]" value="'.$row->group_name.'"></td>';
						else
							print '<td></td>';
						print '</tr>';
					}
					print '<tr class="tr_footer">';
					print '<td colspan="3"><input type="submit" value="Submit"></td>';
					print '</tr>';
				?>
			</table>
		</form>
	</div>
</div>
