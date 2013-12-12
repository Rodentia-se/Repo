<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Test', true);

  $el2 = new htmlElement('span', 'Hej!', array('data-hepp' => 'huff'), 'id');
  $el = new htmlElement('div', 'Hej!', array('href' => 'nlah', 'src' => 'asas'), 'id', 'hidden', array('color' => 'red'));
  $el->addContent($el2);
  $page->addContent($el);
/*
  debug('TEST 1');
  $tournament = tournament('active');
  debug($tournament->getFlat());
  debug('TEST 2');
  $division = division($tournament, 'main');
  debug($division);
  debug('TEST 3');
//  $players = players($tournament, 'eighties');
//  debug($players);
  debug('TEST 4');
  $person = person(2589);
  $player = player($tournament, $person);
  debug($player);
  debug('TEST 5');
  $person = person(2589);
  debug($person);
  debug('TEST 6');
  $players = players($person);
  debug($players);
  debug('TEST 7');
  $player = player($person, 'eighties');
  debug($player);
  debug('TEST 8');
  $player = player($person);
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