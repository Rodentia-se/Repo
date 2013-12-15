<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');

  $obj = (isset($_REQUEST['obj'])) ? $_REQUEST['obj'] : null;
  $id = (isId($_REQUEST['id'])) ? $_REQUEST['id'] : null;
  
  $div = new div();
  
  if (isObj($obj))) {
    $object = $obj($id);
    if ($object) {
      $div->addContent($object->getInfo());
    } else {
      $div->addParagraph('Could not find what you are looking for. Please try again.');
    }
  } else {
    $div->addParagraph('Could not find what you are looking for. Please try again.');
  }
  
  $page->submit();

?>