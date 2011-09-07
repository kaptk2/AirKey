<?php $this->load->helper('form'); ?>
<div class='container'>
	<div class='content'>
		<h3>Current Groups</h3>
		<p>
		<?php echo form_open('group/removeGroup'); ?>
			<table>
				<tr>
					<th>Group Name</th><th>Description</th><th>Delete?</th>
				</tr>
				<?php
					$odd = true;
					foreach($current_groups as $row)
					{
						print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
						print '<td><a href="'.site_url("group/editGroup/".$row->group_name).'">'.$row->group_name.'</td>';
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
		</p>
		<p>
		<?php
			$attributes = array('id' => 'editAP', 'class' => 'inline');
			echo form_open('group/addGroup', $attributes);
		?>
			<fieldset>
				<legend>Add Group</legend>
				<label for="group_name">Name: </label>
				<input type="text" name="group_name">&nbsp;
				<label for="group_desc">Group Description: </label>
				<input type="text" name="group_desc">
				<input type="submit" value="submit">
			</fieldset>
		</form>
		</p>
	</div>
</div>
