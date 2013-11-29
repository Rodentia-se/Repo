<?php

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
  
  function warning($text) {
    preDump('WARNING: '.$text);
  }

  function error($text) {
    preDump('ERROR: '.$text);
  }

  function debug($text) {
    preDump('DEBUG: '.$text);
  }
  
?>