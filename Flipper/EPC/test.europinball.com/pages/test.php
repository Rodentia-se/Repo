<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Test', true);

  $el = new html('div', 'Ytterdiv!', array('style' => 'display: block;'), 'ytterId', 'ytterklass1 ytterklass2', array('color' => 'red', 'display' => 'block'));
  $el2 = $el->addElement('div', 'Innerdiv!', array('class' => 'nlah', 'src' => 'asas'), 'innerId', 'innerKlass', array('color' => 'black'));
  $el2->addElement('span', 'Innerst!', array('data-hepp' => 'huff'), 'spanId');
  $el2->addELement('img', 'imageSrc', array('title' => 'imageTitle'), 'imageId', 'imageKlass', array('width' => '30px'));
  $el2->addContent('Hej på er!');
  $input = new html('input', 'hupp', array('name' => 'heff'), 'input_utan_crlf');
  $input2 = new html('input', 'hupp', array('name' => 'heff', 'type' => 'hidden'), 'input_med_crlf');
  unset($input->crlf);
  debug($input->crlf, 'CRLF');
  $array = array(
    $input,
    'Det här ska komma direkt efter en input, utan crlf',
    1000,
    'Efter det här kommer det en input med $clrf satt till true'
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
  $div->addContent($img);
  $div1 = $el->addDiv('1');
  $div2 = $div1->addDiv('2');
  $div3 = $div2->addDiv('3');
  $div4 = $div3->addDiv('4');
  debug($div4);
  $div4->addJquery('
    $(".edit").change(function(){
           var el = this;
               if (el.id == "shortName") 
               
               
               
               {
             $(el).val($(el).val().toUpperCase());   } 
             var value = ($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val();
          var region_id = (this.id == "city") ? $("#region_id").val() : null;
           var country_id = (this.id == "city" || this.id == "region") ? $("#country_id").val() : null;
             var continent_id = (this.id == "city" || this.id == "region") ? $("#continent_id").val() : null;
             $(el).tooltipster("update", "Updating the database...").tooltipster("show");
             $.post("https://test.europinball.org//ajax/setPlayerProp.php", {prop: el.id, value: value, region_id: region_id, country_id: country_id, continent_id: continent_id})
           .done(function(data) {
              $(el).tooltipster("update", data.reason).tooltipster("show");
              if (data.valid) {
           $(el).data("previous", (($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val()));
               } else {
         if ($(el).is(":checkbox")) {
            el.checked = ($(el).data("previous"));
   } else {
            $(el).val($(el).data("previous"));
     }
             }
     })
.fail(function(jqHXR,status,error) {
    $(el).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
             })
 })
  ');
  debug($img, 'HUFF');
  $img->src = 'a_src';
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