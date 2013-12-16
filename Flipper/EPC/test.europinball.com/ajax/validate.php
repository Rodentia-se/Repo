<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : NULL;
  $prop = (isset($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
  $mandatory = ($_REQUEST['mandatory']) : TRUE : FALSE;
  
  if (!$class) {
    $obj = person('login');
    if ($obj) {
      $class = get_class($obj);
    }
  }
  if ($class) {
    if (isObj($class, TRUE)) {
      if ($prop) {
        $json = $class::validate($prop, $value, TRUE, $mandatory);
      } else {
        $json = failure('Invalid ID');
      }
    } else {
      $json = failure('No property provided');
    }
  } else {
    $json = failure('No class provided');
  }
  
  jsonEcho($json);
  
?>