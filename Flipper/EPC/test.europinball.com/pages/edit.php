<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  if ($page->reqLogin('You need to be logged in and registered as a participant to access this page. Please go to the <a href="'.config::$baseHref.'/reigstration/">registration page</a> or login here:')) {
    $person = $page->login->person;
    if ($person) {
      $page->startDiv('editDiv');
        $page->addContent($person->getEdit());
      $page->closeDiv(); 
      $page->jeditable = TRUE;
      $page->combobox = TRUE;
      $page->addScript('
        $(".edit").editable("'.config::$baseHref.'/setPlayerProp.php", {
          cssclass: "inherit"
        });
        $(".combobox").combobox();
        $(".date").datepicker({
          dateFormat: "yy-mm-dd",
          changeYear: true, 
          yearRange: "-100:-0",
          changeMonth: true 
        });
        $(".addIcon").click(function() {
          $("#" + this.id.replace("add_", "") + "_id").combobox("destroy");
          $("#" + this.id.replace("add_", "") + "_id").hide();
          $("#" + this.id.replace("add_", "")).show();
        })
      ');
    } else {
      error('Could not find you in the database?', TRUE);
    }
  }
  
  $page->submit();

?>