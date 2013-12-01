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

  function error($text, $die = FALSE) {
    preDump($text,'ERROR');
    if ($die) {
      $die('Abort requested.');
    }
  }

  function debug($text, $die = FALSE) {
    if (config::$debug) {
      preDump($text,'DEBUG');
      if ($die) {
        $die('Abort requested.');
      }
    }
  }
  
?>