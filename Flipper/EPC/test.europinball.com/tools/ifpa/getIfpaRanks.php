<?php
  define('__ROOT__', dirname(dirname(dirname(__FILE__)))); 
  require_once(__ROOT__.'/functions/init.php');
  require_once('ifparank.php');
  
  if (!base::$_db) {
    base::$_db = new db();
  }
  $query = 'select id, firstName, lastName, ifpa_id, ifpaRank from person where ifpa_id is not null and ifnull(password, 0) != "checked"';
  $objs = base::$_db->select($query, NULL, 'ifpaPerson');
/*
//    @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
  
    foreach ($objs as $obj) {
      echo '<pre>';
      $rank = get_rank_from_id($obj->ifpa_id);
      var_dump($obj);
      echo 'Found rank: '.$rank['rank'];
      echo 'Setting rank to: '.(($rank['rank'] != -1) ? $rank['rank'] : 0);
      $obj->rank = ($rank['rank'] != -1) ? $rank['rank'] : 0;
      var_dump($obj);
      echo '</pre>';
      $p++;
      for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
      ob_implicit_flush(1);
      if ($obj->rank || $obj->rank === 0) {
        $person = 
        updateIfpaRank($dbh, $obj);
      }
    }
  }
  
  getIfpaPlayers($dbh, true);
*/
debug($objs);
  
?>
