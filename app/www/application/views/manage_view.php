<?php
  foreach($query as $row)
  {
    print $row->id;
    print " ";
    print $row->mac;
    print " ";
    print $row->key;
    print " ";
    print $row->isActive;
    print "<br />";
  }
?>
