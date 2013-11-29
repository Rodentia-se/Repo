<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/init.php');
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
$page = new page(true);

$login = $page->login->reqLogin('Hej!', false);
if ($login === true) {
  preDump($page->login->person);
} else {
  echo $login;
}

preDump($_SESSION);
$person =  new person(array('username' => $_SESSION['username']), true);
preDump($person);
/*
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

?> 