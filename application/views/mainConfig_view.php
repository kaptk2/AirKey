<?php
	echo 'var_server_version=';
	echo $config['mainConfig']->current_version;
	echo "\n";

	echo 'var_modules=';
	echo implode(",", $config['modules']);
	echo "\n";

	echo "var_run='";
	echo $config['mainConfig']->run_command;
	echo "'\n";
?>
