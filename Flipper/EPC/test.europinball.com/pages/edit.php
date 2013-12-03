<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in and registered as a participant to access this page. Please go to the <a href="'.config::$baseHref.'/reigstration/">registration page</a> or login here:')) {
    $person = $page->login->person;
    $player = $person->getPlayer();
    $page->startDiv('editDiv');
      $page->addContent($player->getEdit());
    $page->closeDiv();
    $page->jeditable = TRUE;
    $page->addScript('
      $.editable.addInputType("autocomplete", {
        element: $.editable.types.text.element,
        plugin: function(settings, original) {
          $("input", this).autocomplete(settings.autocomplete.data);
        }
      });
      $(".editText").editable("'.config::$baseHref.'/setPlayerProp.php", {
        cssclass: "inherit"
      });
    ');
  }
  
  $page->submit();

?>