<?php
	// Print out configuration file for the module

	print 'module_name='.$name."\n";
	print 'module_version='.$version."\n";

	// Print out module packages
	print 'var_package=';
	if (!empty($packages))
	{
		foreach($packages as $row)
		{
			print $row->package_name." ";
		}
	}
	print "\n";

	// Print out module files
	if (!empty($files))
	{
		// Print out files location on the server
		print 'var_remote_files=';
		foreach($files as $row)
		{
			print $row->local_file." ";
		}
		print "\n";
		// Print out the files location on the AP
		print 'var_local_files=';
		foreach($files as $row)
		{
			print $row->remote_file." ";
		}
		print "\n";
	}

	// Print out commands
	print 'var_command=';
	if (!empty($commands))
	{
		foreach($commands as $row)
		{
			print $row->command." ";
		}
	}
	print "\n";

?>
