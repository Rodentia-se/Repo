<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  
  switch ($class) {
    case 'division':
      if (isId($id)) {
        $division = division($id);
        if (isDivision($division)) {
          $html = $division->getStandings()
          echo($html->getHtml());
        } else {
          $p = new paragraph('Invalid division');
          echo($p->getHtml());
        }
      } else {
        $p = new paragraph('Invalid division ID');
        echo($p->getHtml());
      }
    break;
    default:
      $p = new paragraph('Invalid class');
      echo($p->getHtml());
    break;
  }

?>          
