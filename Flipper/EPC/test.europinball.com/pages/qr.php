<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  $divisionId = (isset($_REQUEST['division'])) ? $_REQUEST['division'] : NULL;

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    if (isId($divisionId)) {
      $division = division($divisionId);
      if (isDivision($division)) {
        $context = $division;
      }
    }
    if (!$context) {
      $context = tournament('active');
    }
    if (isObj($class, TRUE)) {
      echo '
        <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <link type="text/css" rel="stylesheet" href="'.config::$baseHref.'/css/epc.css">
          </head>
          <body>
      ';
      if (isId($id)) {
        $obj = $class($id);
        echo $obj->getQrLabel();
      } else {
        $class = $class::$arrClass;
        $objs = $class($context);
        foreach ($objs as $obj) {
          $output .= $obj->getQrLabel()."<br /><br />\n";
        }
        echo $output;
      }
      echo '
          </body>
            '.((isset($_REQUEST['autoPrint']) && $_REQUEST['autoPrint']) ? '
              <script type="text/javascript">
                window.print();
              </script>' 
            : '').'
        </html>
      ';
    }
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>