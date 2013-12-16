<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Admin tools');

  $div = new div();
  $division = division('active');
  $players = players($division);
  debug(count($players), 'COUNT');
  $players = players($players);
  debug(count($players), 'COUNT2');
  $persons = persons($players);
  debug(count($persons), 'COUNT3');
  $persons = persons($division);
  debug(count($persons), 'COUNT4');
  $persons = persons('active');
  debug(count($persons), 'COUNT5');
  $persons = persons('main');
  debug(count($persons), 'COUNT6');
  $persons = persons('eighties');
  debug(count($persons), 'COUNT7');
  $persons = persons('');
  debug(count($persons), 'COUNT7');
  $persons = persons('blaha');
  debug(count($persons), 'COUNT7');



/*
  $persons = persons($players);
  debug(count($persons), 'COUNT');

/*  $persons = persons($players);

  $sel = $div->addSelect('persons', $persons);
  $sel->addJquery('change', 'function', '
    
  ');
  $div->addInput(0, 'paid');
  $sel->addJquery('change', 'function', '
    
  ');
  $div->addSpan($person->getCosts(), 'shouldPay');
   
  
  $page->addContent($div);
  $page->submit();
  

/*
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
  $el->addContent($h);
  $el->addContent($b);
  $form = new form('Form', NULL);
  $click = new click('#click', '$("#FORM").submit();');
  $div->addContent($img);
  $div1 = $el->addDiv('1');
  $div2 = $div1->addDiv('2');
  $div3 = $div2->addDiv('3');
  $span2 = $div3->addParagraph('Här är en hög med täcken söm kömmër att blî entities! " € & < > ¢ £ ¥ § © ® ™ ¡ ¤ ± ¿');
  $span2->entities = TRUE;
  $js = new scriptCode('var scriptcode = true; echo "hej"');
  $div4 = $div3->addDiv('4');
  $tooltip = $div4->addTooltip('Hej!');
  $tooltip = $div4->addTooltip('Hej!', FALSE);
  $tooltip = $div4->addTooltip('Hej!', 'update');
  $img->src = 'a_src';
  $div4->addElement($js);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div4->addElement($input2);
  $div3->addSelect('selina', array(0 => 'Choose...', 1 => 'Huff'), 1);
  $el->indents = 5;
$div3->addDiv('ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addSpan('Some content', 'ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addImg('http://www.src', 'titel', array('sampleparam' => 'huff'));
$div3->addLink('http://www.site', 'this is a länk', array('sampleparam' => 'huff'));
$div3->addParagraph('Some content', 'ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addH1('Some content', array('sampleparam' => 'huff'));
$div3->addH2('Some content', array('sampleparam' => 'huff'));
$div3->addH3('Some content', array('sampleparam' => 'huff'));
$div3->addH4('Some content', array('sampleparam' => 'huff'));
$div3->addBr('ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addHr('ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addUl('ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addOl('ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addLi('Lista', 'ID', 'klasses', array('sampleparam' => 'huff'));
$div3->addForm('ID', 'http://www.action', 'GET', array('sampleparam' => 'huff'));
$div3->addLabel('Some content', 'forName', 'ID', 'klasses', array('sampleparam' => 'huff'));
$sel = $div3->addSelect('Namnet1', NULL, NULL, TRUE, array('sampleparam' => 'huff'));
$opt = $sel->addOption('Välj mig!', 'vald', 'vald', array('sampleparam' => 'huff'));
$sel2 = $div3->addSelect('Namnet2', $opt, $opt, TRUE, array('sampleparam' => 'huff'));
$opt2 = $sel->addOption('Välj inte mig!', 'ejvald', FALSE, array('sampleparam' => 'huff'));
$opts = array($opt, $opt2);
$div3->addSelect('Namnet3', $opts, $opt2, FALSE, array('sampleparam' => 'huff'));
$div3->addInput('Namnet', 'Värdet!', 'hidden', NULL, array('sampleparam' => 'huff'));
$div3->addInput('Namnet', 'Värdet!', NULL, TRUE, array('sampleparam' => 'huff'));
$div3->addInput('Namnet', 'Värdet!', 'text', 'Läjbel', array('sampleparam' => 'huff'));
$div3->addHidden('Namnet', 'No', array('sampleparam' => 'huff'));
$div3->addCheckbox('Namnet', TRUE, array('sampleparam' => 'huff'));
$div3->addRadio('Namnet', 'yes', TRUE, array('sampleparam' => 'huff'));
$div3->addRadio('Namnet', 'no', FALSE, array('sampleparam' => 'huff'));
$div3->addButton('Knapp!', 'Namnet', array('sampleparam' => 'huff'));
$div3->addClickButton('Klicka knapp!', 'Namnet', 'http://www.click', TRUE, TRUE, array('sampleparam' => 'huff'));
$div3->addScript('script.js', array('sampleparam' => 'huff'));
$div3->addScript('var kod = true; echo "Vi kodar!"; var merkod = false;', array('sampleparam' => 'huff'));
$div3->addScriptFile('JS.fil', array('sampleparam' => 'huff'));
$div3->addScriptCode('var kod = true; echo "Vi kodar!"; var merkod = false;', array('sampleparam' => 'huff'), 15);
$div3->addTooltip('Some content', FALSE);
$div3->addTooltip('More content', TRUE);
$div3->addTooltip('Most content', 'update');
$div3->addTooltip('Most content', 'new');
$div3->addClick();
$div3->addClick('var click = "klack";');
$div3->addCssFile('http://www.css', array('sampleparam' => 'huff'));

  $person = person(856);
  debug($person->name, "NAME 856"); 
  debug($person->addPlayer(), "ADD");
  $person = person(5768);
  debug($person->name, "NAME 5768"); 
  debug($person->addPlayer(), "ADD");
  $person = person(940);
  debug($person->name, "NAME 940");
  debug($person->addPlayer(), "ADD");
  $person = person(318);
  debug($person->name, "NAME 318");
  debug($person->addPlayer(), "ADD");
  $person = person(18125);
  debug($person->name, "NAME 18125");
  debug($person->addPlayer(), "ADD");
  $person = person(5776);
  debug($person->name, "NAME 5776");
  debug($person->addPlayer(), "ADD");
  $person = person(7521);
  debug($person->name, "NAME 7521");
  debug($person->addPlayer(), "ADD");
  $person = person(5778);
  debug($person->name, "NAME 5778");
  debug($person->addPlayer(), "ADD");

      echo 'HÄRSTARTARDET';
  echo($el);
  
  /*
  $person = person(903);
  debug($person->name, "NAME 903");
  debug($person->addPlayer(), "ADD");
  $person = person(16956);
  debug($person->name, "NAME 16956");
  debug($person->addPlayer(), "ADD");
  $person = person(754);
  debug($person->name, "NAME 754");
  debug($person->addPlayer(), "ADD");
  $person = person(16815);
  debug($person->name, "NAME 16815");
  debug($person->addPlayer(), "ADD");
  $person = person(5784);
  debug($person->name, "NAME 5784");
  debug($person->addPlayer(), "ADD");
  $person = person(7689);
  debug($person->name, "NAME 7689");
  debug($person->addPlayer(), "ADD");
  $person = person(708);
  debug($person->name, "NAME 708");
  debug($person->addPlayer(), "ADD");
  $person = person(952);
  debug($person->name, "NAME 952");
  debug($person->addPlayer(), "ADD");
  $person = person(9798);
  debug($person->name, "NAME 9798");
  debug($person->addPlayer(), "ADD");
  $person = person(6061);
  debug($person->name, "NAME 6061");
  debug($person->addPlayer(), "ADD");
  $person = person(10536);
  debug($person->name, "NAME 10536");
  debug($person->addPlayer(), "ADD");


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
  if ($login);
    $page->content .= 'Logged in!';
  } else;
    $page->content .= 'NOT logged in!';
  }
  $page->submit(FALSE, TRUE);
  $obj = person(1);
      $division = division('active');
  debug($division);
echo 'hej';
foreach($objs as $obj);
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