<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Test', true);

  $el = new html('div', 'Ytterdiv!', array('style' => 'display: block;'), 'ytterId', 'ytterklass1 ytterklass2', array('color' => 'red', 'display' => 'block'));
  $el2 = $el->addElement('div', 'Innerdiv!', array('class' => 'nlah', 'src' => 'asas'), 'innerId', 'innerKlass', array('color' => 'black'));
  $el2->addElement('span', 'Innerst!', array('data-hepp' => 'huff'), 'spanId');
  $el2->addELement('img', 'imageSrc', array('src' => 'newSrc', 'title' => 'imageTitle'), 'imageId', 'imageKlass', array('width' => '30px'));
  $el2->addContent('Hej på er!');
  $input = new html('input', 'hupp', array('name' => 'heff'), 'inputId');
  $input2 = new html('input', 'hupp', array('name' => 'heff', 'type' => 'hidden'), 'inputId');
  unset($input->crlf);
  debug($input->crlf, 'CRLF');
  $array = array(
    $input,
    'jepp!',
    1000, 
    $input2
  );
  $el2->addContent($array);
  $el2->addElement('span', 'Innerst också!', array('class' => 'bold'), 'spanId2');
  $el2->addElement('div', 'Innerdiv3!', array('class' => 'nlah', 'src' => 'asas'), 'innerId3', 'innerKlass', array('color' => 'black'));
  $el->addElement('div', 'Innerdiv4!', array('class' => 'nlah', 'src' => 'asas'), 'innerId4', 'innerKlass', array('color' => 'black'));
  debug($el);
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