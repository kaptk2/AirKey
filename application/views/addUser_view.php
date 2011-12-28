<form method="post" action="addUser">
	<fieldset>
		<legend>Add A New User</legend>
		<?php
			echo form_label('First Name: ', 'fn');
			echo form_input('fn', set_value('fn'), 'id="fn" autofocus');
			echo "<br />";
			echo form_label('Last Name: ', 'ln');
			echo form_input('ln', set_value('ln'), 'id="ln"');
			echo "<br />";
			echo form_label('Email Address: ', 'email');
			echo form_input('email', set_value('email'), 'id="email"');
			echo "<br />";
			echo form_label('Password:', 'password');
			echo form_password('password', '', 'id="password"');
			echo "<br />";
		?>
		<input type="submit" value="Add User" />
	</fieldset>
</form>
<?php echo validation_errors(); ?>
