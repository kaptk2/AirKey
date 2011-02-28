<?php
  $this->load->helper('form');
  echo form_open("modules/$moduleName/$action");

  $data = array(
    'type' => 'hidden',
    'name' => 'applyTo',
    'id' => 'applyTo',
    'value' => 'nothing',
  );
  echo form_input($data);

  $data = array(
    'type' => 'hidden',
    'name' => 'applyType',
    'id' => 'applyType',
    'value' => 'mac',
  );
  echo form_input($data);


  echo "Package Name: ";
  if(!empty($packageName))
    echo form_input('packageName',$packageName);
  else
   echo form_input('packageName');
  echo "<br />";


    echo "Where to put file on AP: ";
    if(!empty($localFile))
      echo form_input('localFile',$localFile);
    else
      echo form_input('localFile');
    echo "<br />";

    echo "Where to get file from: ";
    if(!empty($remoteFile))
      echo form_input('remoteFile',$remoteFile);
    else
      echo form_input('remoteFile');
    echo ' <a href="'.base_url().'fileeditor/edit/'.$moduleName.'" target="_top">Edit files</a>';
    echo "<br />";

    echo "Commands to run: ";
    if(!empty($command))
      echo form_input('command',$command);
    else
      echo form_input('command');
    echo "<br />";

    echo '<p><input type="submit" id="updateButton" name="submit" value="Update Values"></p>';


  echo form_close();
?>
