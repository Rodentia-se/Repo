<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Test', true);

  $tournament = tournament('active');
  debug(1);
  debug($tournament->getFlat());
  $divisions = $tournament->getDivision();
  debug(2);
  debug($divisions);
  $division = division($tournament, 'main');
  debug(3);
  debug($division->getFlat());
  $division = division('current');
  debug(4);
  debug($division->getFlat());
  $players = players($division);
  debug(5);
  debug($players);
  $person = person('auth');
  debug(6);
  debug($person);
  $players = players($person);
  debug(7);
  debug($players);
  $player = player($person, 'eighties');
  debug(8);
  debug($player);
  $player = player($person);
  debug(9);
  debug($player);
  
  
/*
  $obj = new city(1907);
//  $obj = new country(188);

//  $country = new country(188);
//  $obj = $country->getLocations();
  
//  $tournament = new tournament(1);
//  $obj = $tournament->getDivisions();
  
// $obj = new person(array('ifpa_id' => '27', 'mailAddress' => 'the@pal.pp.se'), true);
// $obj = new player(array('ifpa_id' => '27', 'mailAddress' => 'the@pal.pp.se'), true);

// $obj = new person('27', 'ifpa_id');
// $obj = new player('27', 'ifpa_id');
 $objs = new player(1645);
 
 echo "P".property_exists($objs, 'arrClass');
// echo " P2".property_exists($objs, 'arrClass');
// echo " A".$objs->arrClass;
 echo " A2".player::$arrClass;
 preDump(get_class(player::$instances));
 preDump(get_class(city::$instances));
 preDump(get_class(country::$instances));
 preDump(get_class(gender::$instances));
*/
/*
  $page = new page('Test', true);

  $login = $page->reqLogin('Hej!', true);
  if ($login) {
    $page->content .= 'Logged in!';
  } else {
    $page->content .= 'NOT logged in!';
  }
  $page->submit(FALSE, TRUE);
  $obj = person(1);
      $division = division('active');
  debug($division);
echo 'hej';
foreach($objs as $obj) {
/*
  echo '<br><br>';
  echo ($obj->id) ? $obj->id.': '.$obj->firstName.' '.$obj->lastName : 'No ID';  
  */
/*
  preDump($obj);
  preDump($obj->getFlat());
  preDump($obj);
  echo(json_encode($obj));
  preDump($obj);
  $obj->flatten();
  preDump($obj);
}
*/
  $page->submit();

?> 