<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $class (isObj($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id (isId($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  $prop = (is($_REQUEST['prop'])) ? $_REQUEST['prop'] : NULL;
  $value = $_REQUEST['value'];
  
  if ($prop) {
    if ($class) {
      if ($id) {
        $currentObj = $class($id);
      } else if ($class == 'person' || $class = 'team') {
        $currentObj = $class('login')
      }
      $otherObj = $class($value, $prop);
    }
    if (isObj($currrentObj) && $isId($currentObj) && isObj($otherObj) && isId($otherObj->id) && get_class($currentObj) == get_class($otherObj) && $currentObj-> == $otherObj->id) {
      // Identical
    } else {
      if (isObj($class)) {
        $validateioN = $class::validate($prop, $value)
      }
    }
  }

    
?>