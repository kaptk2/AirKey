
<div class='container'>
	<div class='content'>
		<?php
			$attributes = array('id' => 'editModule', 'class' => 'inline');
			echo form_open('module/editModule', $attributes);
		?>
		<input type="hidden" name="old_module" value="<?php echo $module_name; ?>">
			<fieldset>
				<legend>Edit Module: <?php echo $module_name; ?></legend>
				<label for="module_name">New Module Name: </label>
				<input type="text" name="module_name">&nbsp;
				<input type="submit" name="buttons" value="clone" />
				<input type="submit" name="buttons" value="rename" />
				<input type="submit" name="buttons" value="delete this module" />
			</fieldset>
		</form>
	</div>

	<div id='contentLeft'>
		<?php
			echo form_open_multipart('module/upload');?>
			<input type='hidden' name='module_name' value='<?php echo $module_name; ?>' />
			<fieldset>
					<legend>Upload Files</legend>
					<label for="userfile">File Name:</label>
					<!-- Browse Button -->
					<input type="file" name="userfile" size="20" />
					<br />
					<label for="ap_location">Location on Access Point</label>
					<input type="text" name="ap_location" />
					<input type="submit" value="Add File" />
			</fieldset>
		</form>
		<br />
		<br />
		<?php
			if (!empty($files))
			{
				echo form_open('module/manageFiles/'.$module_name);
				// display the table listing of file
				print '<table>';
				print '<tr>';
				print '<th>File Name</th><th>Location On AP</th><th>Delete</th>';
				print '</tr>';
				$odd = true;
				foreach($files as $row)
				{
					print '<input type="hidden" name="orig_names[]" value="'.$row->remote_file.'" />';
					print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
					print '<td><a href="/module/editFile/'.$module_name.'/'.$row->local_file.'">'.$row->local_file.'</a></td>';
					print '<td><input type="text" name="new_names[]" value="'.$row->remote_file.'" /></td>';
					print '<td><input type="checkbox" name="delete[]" value="'.$row->local_file.'"></td>';
					print '</tr>';
				}
				print '<tr class="tr_footer">';
				print '<td colspan="3"><input type="submit" value="Submit"></td>';
				print '</tr>';
				print '</table>';
				print '</form>';
			}
			else
			{
				//no files with this module
				print 'No files associated with this module';
			}
			$attributes = array('id' => 'addFiles', 'class' => 'inline');
			echo form_open('module/editModule', $attributes);
			print '</form>';
		?>
	</div>
	
	<div id='contentRight'>
		<?php
			if (!empty($commands))
			{
				// display the table listing of file
				echo form_open('module/removeCommand');
				print '<input type="hidden" name="module_name" value="'.$module_name.'">';
				print '<ol>';
				foreach($commands as $row)
				{
					print '<li><input type="checkbox" name="cmd_ids[]" value="'.$row->id.'">';
					print $row->command.'</li>';
				}
				print '</ol>';
				print '<input type="submit" value="remove commands">';
				print '</form>';
			}
			else
			{
				//no commands with this module
				print 'No commands associated with this module<br />';
			}

			$attributes = array('id' => 'addCommands', 'class' => 'inline');
			echo form_open('module/addCommand', $attributes);
			print '<input type="hidden" name="module_name" value="'.$module_name.'">';
			print '<fieldset>';
			print '<legend>Add a New Command to the Module</legend>';
			print '<label for="command">Command to Run: </label>';
			print '<input type="text" name="command">&nbsp;';
			print '<input type="submit" value="submit">';
			print '</fieldset>';
			print '</form>';

			if (!empty($packages))
			{
				// display the table listing of file
				echo form_open('module/removePackage');
				print '<input type="hidden" name="module_name" value="'.$module_name.'">';
				print '<ol>';
				foreach($packages as $row)
				{
					print '<li><input type="checkbox" name="pkg_ids[]" value="'.$row->id.'">';
					print $row->package_name.'</li>';
				}
				print '</ol>';
				print '<input type="submit" value="remove packages">';
				print '</form>';
			}
			else
			{
				//no packages with this module
				echo "No packages associated with this module<br>";
			}
			$attributes = array('id' => 'addPackage', 'class' => 'inline');
			echo form_open('module/addPackage', $attributes);
			print '<input type="hidden" name="module_name" value="'.$module_name.'">';
			print '<fieldset>';
			print '<legend>Add a New Package to the Module</legend>';
			print '<label for="package_name">Package: </label>';
			print '<input type="text" name="package_name">&nbsp;';
			print '<input type="submit" value="submit">';
			print '</fieldset>';
			print '</form>';
		?>
	</div>
</div>
