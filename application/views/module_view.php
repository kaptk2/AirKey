<?php $this->load->helper('form'); ?>
<div class='container'>
	<div class='content'>
		<?php
			$attributes = array('id' => 'selectModule', 'class' => 'inline');
			echo form_open('module/index', $attributes);
		?>
			<fieldset>
				<legend>Select A Module To Edit</legend>
				<label for="module_name">Edit Module: </label>
				<?php
					if (!empty($modules))
					{
						print '<select name="module_name">';
						foreach ($modules as $module)
						{
							print '<option value="'.$module->module_name.'">'.$module->module_name.'</option>';
						}
						print '</select>';
						print '<input type="submit" value="submit">';
					}
					else
					{
						//No modules installed
						print '<input type="text" name="module_name" disabled="disabled" value="None Intalled">';
					}
				?>
			</fieldset>
		</form>
		<?php
			$attributes = array('id' => 'createModule', 'class' => 'inline');
			echo form_open('module/createModule', $attributes);
		?>
			<fieldset>
				<legend>Create A New Module</legend>
				<label for="module_name">Module Name: </label>
				<input type="text" name="module_name">&nbsp;
				<input type="submit" value="submit">
			</fieldset>
		</form>
		<h3>Download New Modules</h3>
		<p>todo</p>
	</div>
</div>
