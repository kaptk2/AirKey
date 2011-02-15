<?php
  echo 'var_server_version=';
  if(!empty($currentVersion))
    echo $currentVersion;
  echo "\n";

  echo 'var_modules=';
  if(!empty($modules))
    echo $modules;
  echo "\n";

  echo "var_run='";
  if(!empty($run))
    echo $run;
  echo "'\n";

?>
