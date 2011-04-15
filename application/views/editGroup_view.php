<?php $this->load->helper('form'); ?>
<div class='container'>
	<div id='contentLeft'>
		<?php
			$attributes = array('id' => 'editModules', 'class' => 'inline');
			echo form_open('group/editModules', $attributes);
		?>
			<fieldset>
				<legend>Add or Remove Modules From Group</legend>
				<input type="hidden" name="group_name" value="<?php echo $group_name?>">
				<label for="installed_modules">Installed Module(s)</label><br>
				<select name="installed_modules[]" id="select-from" multiple size="5">
					<?php
						if(empty($installed_modules))
						{
							echo '<option disabled="disabled" value="None">None Installed</option>';
						}
						else
						{
							foreach($installed_modules as $module)
							{
								echo '<option value="'.$module->module_name.'">'.$module->module_name.'</option>';
							}
						}
					?>
				</select>
				<br>

				<label for="new_modules">Available Module(s)</label><br>
				<select name="new_modules[]" id="select-to" multiple size="5">
					<?php
						foreach($all_modules as $module)
						{
							echo '<option value="'.$module->module_name.'">'.$module->module_name.'</option>';
						}
					?>
				</select>
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
				<legend>Modify Group Name or Description</legend>
				<input type="hidden" name="current_group" value="<?php echo $group_name; ?>">
				<label for="current_group">Current Group Name: </label>
				<input type="text" disabled="disabled" name="current_group" value="<?php echo $group_name; ?>">
				<br>
				<label for="new_group">New Group Name: </label>
				<input type="text" name="new_group">
				<label for="moveMembers">Move Members? </label>
				<input type="checkbox" name="moveMembers" value="true">
				<br>
				<label for="group_desc">Group Description: </label><br>
				<textarea name="group_desc"><?php echo $group_desc->group_description; ?></textarea>
				<p><input type="submit" value="submit"></p>
			</fieldset>
		</form>
	</div>
</div>
