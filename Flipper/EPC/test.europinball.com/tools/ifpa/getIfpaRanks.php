<?php
  define('__ROOT__', dirname(dirname(dirname(__FILE__)))); 
  require_once(__ROOT__.'/functions/init.php');
  
  $persons = persons('where ifpa_id is not null and ifnull(password, 0) != "checked"', TRUE);
  
//    @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
  
/*
    foreach ($objs as $obj) {
      echo '<pre>';
      $rank = get_rank_from_id($obj->ifpa_id);
      $obj->rank = ($rank['rank'] != -1) ? $rank['rank'] : 0;
      var_dump($obj);
      echo '</pre>';
      $p++;
      for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
      ob_implicit_flush(1);
      if ($obj->rank || $obj->rank == 0) {
        updateIfpaRank($dbh, $obj);
      }
    }
  }
  
  getIfpaPlayers($dbh, true);
*/

debug($persons);
  
?>