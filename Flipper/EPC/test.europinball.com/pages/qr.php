<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    if (isObj($class, TRUE)) {
      if (isId($id)) {
        $obj = $class($id);
        echo $obj->getQrLabel();
      } else {
        $objs = $class::$arrClass($tournament);
        debug($class);
        debug($objs);
        foreach ($objs as $obj) {
          $output .= $obj->getQrLabel();
        }
        echo $output;
      }
    }
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>