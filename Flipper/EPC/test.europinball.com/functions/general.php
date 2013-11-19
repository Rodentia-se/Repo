<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    }
  });
  
  function isAssoc(&$arr) {
    for (reset($arr); is_int(key($arr)); next($arr));
    return !(is_array($arr) && is_null(key($arr)));
  }

  function preDump($obj) {
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
  }

?>