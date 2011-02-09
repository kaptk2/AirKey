<?php

  echo 'var_package=';
  if(!empty($package))
    echo $package;
  echo "\n";

  echo 'var_remote_files=';
  if(!empty($remoteFile))
    echo $remoteFile;
  echo "\n";

  echo 'var_local_files=';
  if(!empty($localFile))
    echo $localFile;
  echo "\n";

  echo "var_command='";
  if(!empty($command))
    echo $command;
  echo "'\n";
?>
