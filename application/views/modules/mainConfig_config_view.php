<?php
  $this->load->helper('form');
  echo form_open("modules/$moduleName/$action");

  if(!empty($applyTo))
    echo '<input type="hidden" id="applyTo" value="'.$applyTo.'" name="applyTo">';
  else
   echo '<input type="hidden" id="applyTo" name="applyTo">';
  echo "<br />";
  
  if(!empty($applyType))
    echo '<input type="hidden" id="applyType" value="'.$applyType.'" name="applyTo">';
  else
     echo '<input type="hidden" id="applyType" name="applyType">';
  echo "<br />";

  echo "Current Version: ";
  if(!empty($currentVersion))
    echo form_input('currentVersion',$currentVersion);
  else
   echo form_input('currentVersion');
  echo "<br />";

  echo "Modules: ";
  if(!empty($modules))
    echo form_input('modules',$modules);
  else
   echo form_input('modules');
  echo "<br />";

   echo "Run Command: ";
  if(!empty($run))
    echo form_input('run',$run);
  else
   echo form_input('run');
  echo "<br />";

  echo '<p><input type="submit" id="updateButton" name="submit" value="Update Values"></p>';

  echo form_close();
?>
