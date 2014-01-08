<?php
  define('__ROOT__', dirname(dirname(dirname(__FILE__)))); 
  require_once(__ROOT__.'/functions/init.php');
  require_once('ifparank.php');
  
  if (!base::$_db) {
    base::$_db = new db();
  }
  $query = 'select id, firstName, lastName, ifpa_id, ifpaRank from person where ifpa_id is not null and ifpaUpdateReq = 1';
  $persons = base::$_db->select($query, NULL, 'ifpaPerson');
//    @apache_setenv('no-gzip', 1);
  @ini_set('zlib.output_compression', 0);
  @ini_set('implicit_flush', 1);

  foreach ($persons as $person) {
    echo '<pre>';
    var_dump($person);
    $rank = get_rank_from_id($person->ifpa_id);
    echo 'Found rank: '.$rank['rank']."\n";
    if ($rank['rank'] || $rank['rank'] === 0) {
      echo 'Setting rank to: '.(($rank['rank'] != -1) ? $rank['rank'] : 0)."\n";
      $person->updateRank((($rank['rank'] != -1) ? $rank['rank'] : 0));
    }
    echo '</pre>';
    $p++;
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);
  }
  
  getIfpaPlayers($dbh, true);
  
?>
