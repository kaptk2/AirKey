<form name="editarea" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<textarea rows="24" cols="80" name="fileContents"><?php echo $fileContents; ?></textarea>
	<br />
	<input type="submit" value="Save File" />
	<input type="reset" value="Cancel" />
</form>
