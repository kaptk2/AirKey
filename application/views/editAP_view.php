<div class='container'>
	<div class='content'>
	<?php $this->load->helper('form'); ?>
	<?php
		$attributes = array('id' => 'addCommand', 'class' => 'inline');
		echo form_open('manage/setCommand', $attributes);
	?>
		<fieldset>
			<legend>Add Command</legend>
			<input type="hidden" name="mac" value="<?php echo $mac; ?>">
			<label for="command">Command to run: </lable>
			<input type="text" name="command" value="<?php echo $command->run_command; ?>">
			<input type="submit" name="add" value="Add Command">
			<input type="submit" name="remove" value="Remove Command">
		</fieldset>
	</form>
	<?php
		$attributes = array('id' => 'editAP');
		echo form_open('manage/editAP', $attributes);
	?>
		<fieldset>
			<legend>Edit AP infomation</legend>

			<input type="hidden" name="mac" value="<?php echo $mac; ?>">
			<label for="ap_name">Name: </label>
			<?php
				if(!empty($name))
					echo '<input type="text" name="ap_name" value="'.$name.'"> <br>';
				else
					echo '<input type="text" name="ap_name" value=""> <br>';
				echo '<label for="group">Group: </label><br>';
				echo '<select name="group">';
				foreach($groups as $row)
				{
					if ($currentGroup->group_name == $row->group_name)
						echo '<option value="'.$row->group_name.'" selected="selected">'.$row->group_name.'</option>';
					else
						echo '<option value="'.$row->group_name.'">'.$row->group_name.'</option>';
				}
				echo "</select>";
			?>
			<br>
			<label for="notes">Notes: </label>
			<br>
			<textarea name="notes"><?php echo $notes; ?></textarea>
			<br>
			<label for="location">Location: </label>
			<br>
			<textarea name="location"><?php echo $location; ?></textarea>
			<p><input type="submit" value="submit"></p>
		</fieldset>
	</form>
	</div>
</div>
