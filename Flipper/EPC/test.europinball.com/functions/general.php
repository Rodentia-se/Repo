<?php

  require_once('classes.php');

  function isAssoc(&$arr) {
    if (is_array($arr)) {
      for (reset($arr); is_int(key($arr)); next($arr));
      return !is_null(key($arr));
    } else {
      return false;
    }
  }

  function preDump($obj) {
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
  }
  
?>