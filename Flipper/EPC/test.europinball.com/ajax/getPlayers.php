<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

   $search = (isset($_REQUEST['search'])) ? $_REQUEST['type'] : null;
   
   if ($search) {
     $persons = persons($search);
     debug($persons);
   }
   


?>