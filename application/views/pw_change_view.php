<form method="post" action="changePass/<?php echo $id ?>">
	<fieldset>
		<legend>Change password</legend>
		<?php
			echo form_label('Password:', 'password');
			echo form_password('password', '', 'id="password"');
			echo "<br />";
		?>
		<input type="submit" value="Change" />
	</fieldset>
</form>
<?php echo validation_errors(); ?>
