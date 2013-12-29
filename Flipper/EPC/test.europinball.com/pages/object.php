<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Info');

  $obj = (isset($_REQUEST['obj'])) ? $_REQUEST['obj'] : null;
  $id = (isId($_REQUEST['id'])) ? $_REQUEST['id'] : null;
  
  $div = new div();
  
  if (isObj($obj, TRUE)) {
    $object = $obj($id);
    debug($object);
    if ($object) {
      if ($obj == 'machine') {
        $object = $object->game;
        debug($object);
      }
      $div->addContent($object->getInfo());
      $div->addDiv(NULL, 'clearer');
      $childrenTabs = $object->getChildrenTabs();
      if ($childrenTabs) {
        $div->addContent($childrenTabs);
      }
    } else {
      $div->addParagraph('Could not find the '.$obj.' you are looking for. Please try again.');
    }
  } else {
    $div->addParagraph('Could not find what you are looking for. Please try again.');
  }
  
  $page->addContent($div);
  $page->datatables = TRUE;
  
  $page->submit();

?>