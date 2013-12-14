<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $el = new html('div', 'Ytterdiv!', array('style' => 'display: block;'), 'ytterId', 'ytterklass1 ytterklass2', array('color' => 'red', 'display' => 'block'));
  $el2 = $el->addElement('div', 'Innerdiv!', array('class' => 'nlah', 'src' => 'asas'), 'innerId', 'innerKlass', array('color' => 'black'));
  $el2->addElement('span', 'Innerst!', array('data-hepp' => 'huff'), 'spanId');
  $span = new span ('Man kan ju ävan stoppa in mina HTML-obj i arrayen!', NULL, 'spanKlass', array('parameter' => 'random parameter'));
  $el2->addELement('img', 'imageSrc', array('title' => 'imageTitle'), 'imageId', 'imageKlass', array('width' => '30px'));
  $el2->addContent('Hej på er!');

  $input = new html('input', 'hupp', array('name' => 'heff'), 'input_utan_crlf');
  $input2 = new html('input', 'hupp', array('name' => 'heff', 'type' => 'hidden'), 'input_med_crlf');
  unset($input->crlf);
  $array = array(
    $input,
    'Det här ska komma direkt efter en input, utan crlf',
    'Det här är en array, med text',
    'Det blir inga mellanslag emellan, eftersom array-värdena inte innehåller mellanslag',
    'Funderar på att lägga till det och kanske även en punkt (om det inte redan finns), så att man kan lagra meningar på varnandra, men förstör man nåt då?',
    'Den kommer ju att lägga dit mellanslag även för andra pryttlar... men jag kan ju kolla att content inte är ett HTML-obj och inte Javascript?',
    $span,
    'Nu är det slut på arrayen, och efter det här kommer det en input med $clrf satt till true'
  );
  $el2->addContent($array);
  $el2->addContent($input2);
  $el2->addContent('Det här ska komma efter en input och en crlf');
  $el2->addElement('span', 'Innerst också!', array('class' => 'bold'), 'spanId2');
  $el2->addContent('Lite konstiga tecken som ska funka i html: &"<>\'');
  $img = $el2->addELement('img', 'imageSrc', array('src' => 'another_src', 'title' => 'imageTitle'), 'imageId', 'imageKlass', array('width' => '30px'));
  $el2->addElement('div', 'Innerdiv3!', array('class' => 'nlah', 'src' => 'asas'), 'innerId3', 'innerKlass', array('color' => 'black'));
  $el->addElement('div', 'Innerdiv4!', array('class' => 'nlah', 'src' => 'asas'), 'innerId4', 'innerKlass', array('color' => 'black'));
  $div = $el->addDiv('newDiv', 'newDivKlass', array('title' => 'newDivTitle'));
  $h = new hidden('action', 'login');
  $b = new clickButton('Click me!');
  echo($b->script->getHtml());
  $el->addContent($h);
  $el->addContent($b);
  $form = new form('Form', NULL);
  $click = new click('#click', '$("#FORM").submit();');
  echo 'HÄRSTARTARDET';
  echo $b;
/*
  $div->addContent($img);
  $div1 = $el->addDiv('1');
  $div2 = $div1->addDiv('2');
  $div3 = $div2->addDiv('3');
  $span2 = $div3->addParagraph('Här är en hög med täcken söm kömmër att blî entities! " € & < > ¢ £ ¥ § © ® ™ ¡ ¤ ± ¿');
  $span2->entities = TRUE;
  $js = new scriptCode('var scriptcode = true; echo "hej"');
  debug($js);
  $div4 = $div3->addDiv('4');
  $tooltip = $div4->addTooltip('Hej!');
  $tooltip = $div4->addTooltip('Hej!', FALSE);
  $tooltip = $div4->addTooltip('Hej!', 'update');
  debug($tooltip);
  $img->src = 'a_src';
  $div4->addElement($js);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $el->indents = 5;
  */
//  echo($el);
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

?> 