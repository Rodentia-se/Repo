<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    }
  });

  function pre_dump($obj) {
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
  }

?>