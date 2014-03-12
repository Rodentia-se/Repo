<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
    if (isObj($class, TRUE)) {
      echo '
        <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          </head>
          <body>
      ';
      if (isId($id)) {
        $obj = $class($id);
        echo $obj->getQrLabel();
      } else {
        $class = $class::$arrClass;
        $objs = $class($tournament);
        foreach ($objs as $obj) {
          $output .= $obj->getQrLabel()."<br /><br />\n";
        }
        echo $output;
      }
      echo '</body></html>';
    }
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>