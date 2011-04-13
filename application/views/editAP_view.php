<div class='container'>
	<div class='content'>
	<?php
		$this->load->helper('form');
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
			<textarea name="notes">
				<?php if(!empty($notes)) { echo $notes; } ?>
			</textarea>
			<br>
			<label for="location">Location: </label>
			<br>
			<textarea name="location">
				<?php if(!empty($location)) { echo $location; } ?>
			</textarea>
			<p><input type="submit" value="submit"></p>
		</fieldset>
	</form>
	</div>
</div>
