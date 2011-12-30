<?php
	echo form_open(uri_string());
	echo form_textarea('contents', $contents);
	echo "<br />";
	echo form_submit('fileEdit', 'Save Changes');
	echo form_close();
?>
