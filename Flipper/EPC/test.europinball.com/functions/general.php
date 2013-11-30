<?php

  function isAssoc(&$arr) {
    if (is_array($arr)) {
      for (reset($arr); is_int(key($arr)); next($arr));
      return !is_null(key($arr));
    } else {
      return false;
    }
  }

  function preDump($obj, $title = NULL) {
    echo '<pre>';
    echo ($title) ? $title.':' : '';
    var_dump($obj);
    echo '</pre>';
  }
  
  function warning($text) {
    preDump($text,'WARNING');
  }

  function error($text) {
    preDump($text,'ERROR');
  }

  function debug($text) {
    preDump($text,'DEBUG');
  }
  
?>