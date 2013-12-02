<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

   $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;
   
   echo "hej".search;
   
   if ($search) {
     $persons = persons($search);
     if ($persons) {
       $json = (object) array(
         'sEcho' => $_REQUEST['sEcho'],
         'iTotalRecords' => count($persons),
         'iTotalDisplayRecords' => count($persons),
       );
     }
     debug($persons);
   }
   


?>