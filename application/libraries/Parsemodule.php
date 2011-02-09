<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parsemodule {

  function parseConfig($file)
  {
    $lines = file($file);
    $config = array();

    foreach ($lines as $line_num=>$line) {
      # Comment?
      if ( ! preg_match("/#.*/", $line) ) {
        # Contains non-whitespace?
        if ( preg_match("/\S/", $line) ) {
          list( $key, $value ) = explode( "=", trim( $line ), 2);
          $config[$key] = $value;
        }
      }
    }
    return $config;
  }

}

?>
