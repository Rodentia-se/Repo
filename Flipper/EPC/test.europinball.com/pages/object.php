<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Info');

  $obj = (isset($_REQUEST['obj'])) ? $_REQUEST['obj'] : null;
  $id = (isId($_REQUEST['id'])) ? $_REQUEST['id'] : null;
  
  $div = new div();
  
  if (isObj($obj, TRUE)) {
    $object = $obj($id);
    if ($object) {
      $context = (get_class($object) == 'player') ? division($object->tournamentDivision) : getTournament();
      if (isTournament($context) || isDivision($context)) {
        $arrClass = $obj::$arrClass;
        $objs = $arrClass($context);
        $selectDiv = $div->addDiv();
        $selectDiv->addContent($objs->getSelectObj(NULL, $object));
      }
      $div->addContent($object->getInfo());
    } else {
      $div->addParagraph('Could not find the '.$obj.' you are looking for. Please try again.');
    }
  } else {
    $div->addParagraph('Could not find what you are looking for. Please try again.');
  }
  
  $page->addContent($div);
  
  $page->submit();

?>