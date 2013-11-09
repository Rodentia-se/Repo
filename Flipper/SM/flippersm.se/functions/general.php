<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  define('__mainQualLimit__', '50'); 
  define('__classicsQualLimit__', '32'); 
  define('__baseHref__', 'https://www.flippersm.se');
  require_once(__ROOT__.'/functions/db.php');
  require_once(__ROOT__.'/contrib/ulogin/config/all.inc.php');
  require_once(__ROOT__.'/contrib/ulogin/main.inc.php');
  require_once(__ROOT__.'/functions/auth.php');
  require_once(__ROOT__.'/functions/header.php');

  define('__tshirtsDisabled__', false);

  $baseHref = __baseHref__;

  header('Access-Control-Allow-Origin: https://flippersm.se,https://www.flippersm.se');

  $exts = array('png', 'jpg', 'jpeg', 'jpg');

  spl_autoload_register(function($class) { // Autoloading classes. To fix some day: For some reason, some of the classes require require_once:s - which they shouldn't. 
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    }
  });  
  
  $classes = (object) array(
    'game' => (object) array(
      'name' => 'game', 'geo' => false, 'plural' => 'games', 'label' => 'Game', 'table' => 'game', 'column' => 'game_id',
      'headers' => array('name', 'acronym', 'manufacturer', 'year', 'isIpdb', 'rules'), // Headers normally used in tables and lists
      'info' => array('name', 'type', 'acronym', 'manufacturer', 'year', 'isIpdb', 'rules'), // Headers normally used in info divs
      'parents' => array('manufacturer'), 'selfParent' => false, 'id2name' => false, 'acronym' => 'Ga',
      'children' => false,
      'fields' => (object) array(
        'name' => (object) array(
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'type' => (object) array(
          'label' => 'Typ',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        ),
        'acronym' => (object) array(
          'label' => 'Kortnamn',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        ),
        'manufacturer' => (object) array(
          'label' => 'Tillverkare',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        ),
        'year' => (object) array(
          'label' => 'År',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        ),
        'isIpdb' => (object) array(
          'label' => 'IPDB',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        ),
        'rules' => (object) array(
          'label' => 'Regler',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        )
      ),
      'complete' => false
    ),
    'machine' => (object) array(
      'name' => 'machine', 'plural' => 'machines', 'table' => 'machine', 'column' => 'machine_id',
      'parents' => array('game', 'manufacturer', 'tournamentDivision'), 'id2name' => array('game', 'manufacturer'),
      'children' => false,
      'selfParent' => false, 'acronym' => 'Ma'
    ),
    'qualGroup' => (object) array(
      'name' => 'qualGroup', 'plural' => 'qualGroups', 'table' => 'qualGroup', 'column' => 'qualGroup_id',
      'info' => array('shortName', 'date', 'startTime', 'endTime', 'noOfPlayers'), // Headers normally used in info divs
      'parents' => false,
      'children' => false,
      'selfParent' => false, 'acronym' => 'qg',
      'fields' => (object) array(
        'shortName' => (object) array(
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'date' => (object) array(
          'label' => 'Datum',
          'type' => 'date',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'startTime' => (object) array(
          'label' => 'Start',
          'type' => 'time',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'endTime' => (object) array(
          'label' => 'Slut',
          'type' => 'time',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'noOfPlayers' => (object) array(
          'label' => 'Spelare',
          'type' => 'text',
          'mandatory' => true,
          'special' => false,
          'bundle' => false,
          'insert' => true,
          'default' => ''
        )
      )
    ),
    'tournamentDivision' => (object) array(
      'name' => 'tournamentDivision', 'table' => 'tournamentDivision', 'column' => 'tournamentDivision_id',
      'parents' => array('tournamentEdition'), 'id2name' => false,
      'children' => false,
      'selfParent' => false, 'acronym' => 'Td'
    ),
    'tournamentEdition' => (object) array(
      'name' => 'tournamentEdition', 'table' => 'tournamentEdition', 'column' => 'tournamentEdition_id', 'acronym' => 'Te',
      'parents' => false, 'id2name' => false,
      'children' => false,
    ),
    'manufacturer' => (object) array(
      'name' => 'manufacturer', 'geo' => false, 'plural' => 'manufacturers', 'table' => 'manufacturer', 'column' => 'manufacturer_id', 'acronym' => 'Mn',
      'headers' => array('name', 'link'), // Headers normally used in tables and lists
      'info' => array('name', 'link'), // Headers normally used in info divs
      'parents' => false, 
      'children' => array('game'),
      'id2name' => false,
      'fields' => (object) array(
        'name' => (object) array(
          'label' => 'Namn',
          'type' => 'text',
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''
        ),
        'link' => (object) array(
          'label' => 'Länk',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => ''
        )
      ),
      'complete' => false
    ),
    'continent' => (object) array(
      'name' => 'continent', 'geo' => true, 'plural' => 'continents', 'table' => 'continent', 'column' => 'continent_id', 'acronym' => 'Cn', 
      'headers' => array('name', 'latitude', 'longitude'), // Headers normally used in tables and lists
      'info' => array('name', 'latitude', 'longitude'), // Headers normally used in info divs
      'parents' => false,
      'children' => array('country', 'region', 'city', 'player', 'team'),
      'id2name' => false,
      'fields' => (object) array(
        'name' => (object) array(
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'latitude' => (object) array(
          'label' => 'Latitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        ),
        'longitude' => (object) array(
          'label' => 'Longitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        )
      ),
      'complete' => false
    ),
    'country' => (object) array(
      'name' => 'country', 'geo' => true, 'plural' => 'countries', 'table' => 'country', 'column' => 'country_id',
      'headers' => array('name', 'continent', 'latitude', 'longitude'), // Headers normally used in tables and lists
      'info' => array('name', 'altName', 'parentCountry', 'continent', 'latitude', 'longitude'), // Headers normally used in info divs
      'parents' => array('continent'),
      'children' => array('region', 'city', 'player', 'team'),
      'id2name' => array('continent'),
      'selfParent' => true,
      'acronym' => 'Co',
      'fields' => (object) array(
        'name' => (object) array( 
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'altName' => (object) array( 
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'continent' => (object) array(
          'label' => 'Kontinent',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'insert' => true,
          'bundle' => false  
        ),
        'parentCountry' => (object) array(  
          'label' => 'Tillhör land',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'latitude' => (object) array(  
          'label' => 'Latitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'longitude' => (object) array(  
          'label' => 'Longitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        )
      ),
      'complete' => false
    ),
    'region' => (object) array(
      'name' => 'region', 'geo' => true, 'plural' => 'regions', 'table' => 'region', 'column' => 'region_id',
      'headers' => array('name', 'country', 'continent', 'latitude', 'longitude'),  // Headers normally used in tables nd lists
      'info' => array('name', 'altName', 'parentRegion', 'country', 'parentCountry', 'continent', 'latitude', 'longitude'), // Headers normally used in info divs
      'parents' => array('country', 'continent'),
      'children' => array('city', 'player'),
      'id2name' => array('country', 'continent'),
      'selfParent' => true, 'acronym' => 'Re',
      'fields' => (object) array(
        'name' => (object) array(  
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'altName' => (object) array( 
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'continent' => (object) array(  
          'label' => 'Kontinent',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => 1  
        ),
        'country' => (object) array(  
          'label' => 'Land',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => 1  
        ),
        'parentRegion' => (object) array(
          'label' => 'Landsdel',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'parentCountry' => (object) array(  
          'label' => 'Tillhör land',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'latitude' => (object) array(  
          'label' => 'Latitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'longitude' => (object) array(  
          'label' => 'Longitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        )
      ),
      'complete' => false
    ),
    'city' => (object) array(
      'name' => 'city', 'table' => 'city', 'column' => 'city_id', 'geo' => true, 'plural' => 'cities',
      'headers' => array('name', 'region', 'country', 'continent', 'latitude', 'longitude'), // Headers normally used in tables and lists
      'info' => array('name', 'altName', 'region', 'parentRegion', 'country', 'parentCountry', 'continent', 'latitude', 'longitude'), // Headers normally used in info divs
      'parents' => array('region', 'country', 'continent'),
      'children' => array('player'),
      'id2name' => array('region', 'country', 'continent'),
      'selfParent' => false,
      'acronym' => 'Ci',
      'fields' => (object) array(
        'name' => (object) array(  
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'altName' => (object) array( 
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'continent' => (object) array(  
          'label' => 'Kontinent',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => 1  
        ),
        'country' => (object) array(  
          'label' => 'Land',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => 1  
        ),
        'region' => (object) array(  
          'label' => 'Landskap',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => 1  
        ),
        'parentRegion' => (object) array(
          'label' => 'Landsdel',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'parentCountry' => (object) array(  
          'label' => 'Tillhör land',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'latitude' => (object) array(  
          'label' => 'Latitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'longitude' => (object) array(  
          'label' => 'Longitud',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''
        )
      ),
      'complete' => false
    ),
    'person' => (object) array(
      'name' => 'person', 'table' => 'person', 'column' => 'person_id',
      'parents' => array('city', 'region', 'country', 'continent', 'gender'),
      'children' => false,
      'id2name' => array('gender', 'city', 'region', 'country', 'continent'),
      'selfParent' => false, 'acronym' => 'Pe'
    ), 
    'player' => (object) array(
      'name' => 'player', 'label' => 'spelare', 'geo' => false, 'plural' => 'spelare', 'table' => 'player', 'column' => 'player_id',
      'headers' => array('name', 'initials', 'city', 'region', 'country', 'continent'), // Headers normally used in tables and lists
      'info' => array('name', 'initials', 'qualGroup', 'birthDate', 'gender', 'city', 'region', 'parentRegion', 'country', 'parentCountry', 'continent', 'isIfpa', 'main', 'classics', 'volunteer'), // Headers normally used in info divs
      'parents' => array('person', 'city', 'region', 'country', 'continent', 'tournamentDivision', 'tournamentEdition', 'gender'),
      'children' => false,
      'id2name' => array('gender', 'city', 'region', 'country', 'continent'),
      'selfParent' => false, 'acronym' => 'Pl',
      'fields' => (object) array(
        'id' => (object) array(  
          'label' => 'ID',  
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => 0  
        ),
        'name' => (object) array(  
          'label' => 'Namn',
          'type' => '',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => null 
        ),
        'ifpa_id' => (object) array(  
          'label' => 'IFPA ID',  
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => 0  
        ),
        'ifpaRank' => (object) array(  
          'label' => 'Rankad',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => null 
        ),
        'class' => (object) array(  
          'label' => 'Klass',
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => 'player'  
        ),
        'isPlayer' => (object) array(  
          'label' => 'isPlayer',  
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => true  
        ),
        'isPerson' => (object) array(  
          'label' => 'isPerson',  
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => true  
        ),
        'isIfpa' => (object) array(  
          'label' => 'IFPA-rankad',
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => true  
        ),
        'firstName' => (object) array(  
          'label' => 'Förnamn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'lastName' => (object) array(  
          'label' => 'Efternamn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'initials' => (object) array(  
          'label' => 'TAG',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'username' => (object) array(  
          'label' => 'Användarnamn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => ''  
        ),
        'password' => (object) array(  
          'label' => 'Lösenord',
          'type' => 'password',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => ''  
        ),
        'passwordRequired' => (object) array(  
          'label' => 'Lösenord',
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => false,
          'default' => true  
        ),
        'gender' => (object) array(  
          'label' => 'Kön',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''
        ),
        'streetAddress' => (object) array(  
          'label' => 'Gatuadress',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'zipCode' => (object) array(  
          'label' => 'Postnr',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'city' => (object) array(  
          'label' => 'Hemort',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => 'add',  
          'bundle' => false,
          'insert' => true,
          'default' => ''  
        ),
        'region' => (object) array(  
          'label' => 'Landskap',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => 'add',  
          'bundle' => false,
          'insert' => true,
          'default' => ''  
        ),
        'country' => (object) array(  
          'label' => 'Land',
          'type' => 'select',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''  
        ),
        'parentRegion' => (object) array(
          'label' => 'Landsdel',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'parentCountry' => (object) array(  
          'label' => 'Tillhör land',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => false,
          'default' => ''  
        ),
        'continent' => (object) array(  
          'label' => 'Kontinent',
          'type' => 'select',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,
          'insert' => true,
          'default' => ''            
        ),
        'telephoneNumber' => (object) array(  
          'label' => 'Telefon',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'mobileNumber' => (object) array(  
          'label' => 'Mobil',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'mailAddress' => (object) array(  
          'label' => 'Mailadress',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'main' => (object) array(  
          'label' => 'Main',  
          'type' => 'checkbox',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => 1,  
          'insert' => true,
          'default' => true  
        ),
        'classics' => (object) array(  
          'label' => 'Classics',  
          'type' => 'checkbox',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => 1,  
          'insert' => true,
          'default' => true  
        ),
        'volunteer' => (object) array(  
          'label' => 'Volontär',
          'type' => 'checkbox',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => true  
        ),
        'birthDate' => (object) array(  
          'label' => 'Född',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => 'date',  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'dateRegistered' => (object) array(  
          'label' => 'Anmäld',
          'type' => 'hidden',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => date('Y-m-d')
        )
      ),
      'complete' => false
    ),
    'gender' => (object) array(
      'name' => 'gender', 'geo' => false, 'plural' => 'genders', 'table' => 'gender', 'column' => 'gender_id', 'acronym' => 'Ge',
      'headers' => array('name'),
      'parents' => false, 'id2name' => false,
      'mandatory' => array('name'),
      'fields' => (object) array(
        'name' => (object) array(  
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        )
      ),
      'complete' => true
    ),
    'team' => (object) array(
      'name' => 'team', 'geo' => false, 'plural' => 'teams', 'table' => 'team', 'column' => 'team_id', 'acronym' => 'Tm',
      'headers' => array('name', 'initials'),
      'info' => array('name', 'initials'), // Headers normally used in info divs
      'mandatory' => array('name'),
      'parents' => array('city', 'region', 'country', 'continent', 'tournamentDivision', 'tournamentEdition'),
      'children' => false,
      'id2name' => array('city', 'region', 'country', 'continent'),
      'fields' => (object) array(
        'name' => (object) array(  
          'label' => 'Namn',
          'type' => 'text',  
          'mandatory' => true,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        ),
        'initials' => (object) array(  
          'label' => 'TAG',
          'type' => 'text',  
          'mandatory' => false,  
          'special' => false,  
          'bundle' => false,  
          'insert' => true,
          'default' => ''  
        )
      ),
      'complete' => true
    )
  );
  $GLOBALS['classes'] = $classes;
  
  $geoTypes = array('continent', 'country', 'region', 'city');
  $playerHeaders = array('firstName', 'lastName', 'username', 'password', 'initials', 'ifpa_id', 'ifpaRank', 'gender', 'streetAddress', 'zipCode', 'city', 'region', 'country', 'continent', 'telephoneNumber', 'mobileNumber', 'mailAddress', 'dateRegistered', 'birthDate', 'main', 'classics', 'volunteer', 'id', 'comment');
  $playerLabels = array('Förnamn', 'Efternamn', 'Användarnamn', 'Lösenord', 'TAG', 'IFPA ID', 'Rankad',  'Kön', 'Gatuadress', 'Postnr', 'Stad', 'Landskap', 'Land', 'Kontinent', 'Telefon', 'Mobil', 'Mailadress', 'Anmäld', 'Född', 'Main', 'Classics', 'Volontär', 'ID', 'Kommentar');
  $playerTypes = array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'select', 'text', 'text', 'select', 'select', 'select', 'select', 'text', 'text', 'text', 'text', 'text', 'checkbox', 'checkbox', 'checkbox', 'text', 'text');
  //  $debug = $_GET['debug'];
  
  function getObject($dbh, $class, $id) {
    $query = 'select * from '.$class.' where id = '.$id;
    $newClass = $class;
    switch ($class) {
      case 'player': 
        $query = 'select * from '.$class.' where person_id = '.$id.' and (tournamentDivision_id = 1 or tournamentDivision_id = 2) group by person_id';
      break;
      case 'person': 
        $newClass = 'player';
      break;
      case 'tournamentDivision': 
        $newClass = 'stdClass';
      break;
      case 'tournamentEdition': 
        $newClass = 'stdClass';
      break;
    }
    $sth = $dbh->query($query);
    $obj = $sth->fetchObject($newClass);
    switch ($class) {
      case 'player':
        $obj->id = $obj->person_id;
      break;
      case 'person':
        $obj->id = $obj->person_id;
        $obj->class = 'person';
      break;
      case 'tournamentDivision':
        $obj->class = 'tournamentDivision';
      break;
      case 'tournamentEdition':
        $obj->class = 'tournamentEdition';
      break;
    }
    return $obj;
  }
  
  function getObjects($dbh, $class, $query) {
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject($class)) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getError($msg, $tr = true, $colspan = 7) {
    $errorMsg = $msg.', please <a href="JavaScript:window.location.reload()">reload the page</a> and try again, or <a href="mailto:support@flippersm.se">contact us</a>.';
    if ($tr) {
      $errorMsg = '<tr><td colspan="'.$colspan.'">'.$errorMsg.'</td></tr>';
    }
    $error = (object) array('success' => false, 'reason' => $msg, 'longReason' => $errorMsg);    
    return json_encode($error);
  }
    
  function getTshirtSelect() {
    return '
      select 
        ts.id as id,
        ts.name as name,
        "tshirt" as class,
        pt.number as number,
        pt.number as number_id,
        pt.person_id as player_id,
        pt.id as playerTshirt_id,
        tc.name as color,
        tc.id as color_id,
        tz.name as size,
        tz.id as size_id,
        tt.id as tournamentTshirt_id,
        te.name as tournamentEdition,
        te.id as tournamentEdition_id
      from tournamentTShirt tt
        left join tshirt ts on tt.tshirt_id = ts.id
        left join tournamentEdition te on tt.tournamentEdition_id = te.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
        left join personTShirt pt on pt.tournamentTShirt_id = tt.id
    ';
  }  
    
  function getTshirts($dbh, $tournament = 1) {
    $query = getTshirtSelect().' where te.id = '.$tournament;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('tshirt')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getTshirtBuyers($dbh, $tournament = 1) {
    $query = getPlayerSelect();
    $query .= '
      left join personTShirt pt 
        on pt.person_id = p.id 
      where pt.id is not null
        and m.tournamentEdition_id = '.$tournament.'
      order by p.firstName, p.lastName
    ';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('player')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getTshirtOrders($dbh, $tournament = 1) {
    $query = '
      select 
        ts.id as id,
        pt.name as name,
        "tshirt" as class,
        pt.number as number,
        pt.number as number_id,
        pt.person_id as player_id,
        concat(ifnull(pt.firstName, ""), " ", ifnull(pt.lastName, "")) as player,
        pt.telephoneNumber as telephoneNumber,
        pt.mobileNumber as mobileNumber,
        pt.mailAddress as mailAddress,
        pt.dateDelivered as dateDelivered,
        pt.id as playerTshirt_id,
        tc.name as color,
        tc.id as color_id,
        tz.name as size,
        tz.id as size_id,
        tt.id as tournamentTshirt_id,
        te.name as tournamentEdition,
        te.id as tournamentEdition_id
      from personTShirt pt
        left join tournamentTShirt tt on pt.tournamentTShirt_id = tt.id
        left join tshirt ts on tt.tshirt_id = ts.id
        left join tournamentEdition te on tt.tournamentEdition_id = te.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
    ';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('tshirt')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getTshirtOrderById($dbh, $id) {
    $query = '
      select 
        ts.id as id,
        pt.name as name,
        "tshirt" as class,
        pt.number as number,
        pt.number as number_id,
        pt.person_id as player_id,
        concat(ifnull(pt.firstName, ""), " ", ifnull(pt.lastName, "")) as player,
        pt.telephoneNumber as telephoneNumber,
        pt.mobileNumber as mobileNumber,
        pt.mailAddress as mailAddress,
        pt.dateDelivered as dateDelivered,
        pt.id as playerTshirt_id,
        tc.name as color,
        tc.id as color_id,
        tz.name as size,
        tz.id as size_id,
        tt.id as tournamentTshirt_id,
        te.name as tournamentEdition,
        te.id as tournamentEdition_id
      from personTShirt pt
        left join tournamentTShirt tt on pt.tournamentTShirt_id = tt.id
        left join tshirt ts on tt.tshirt_id = ts.id
        left join tournamentEdition te on tt.tournamentEdition_id = te.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
      where pt.id = '.$id;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('tshirt')) {
      return $obj;
    }
    return false;
  }
  
  
  function getTshirtById($dbh, $tshirtId) {
    $query = getTshirtSelect().' where pt.id = '.$tshirtId;
    $sth = $dbh->query($query);
    if ($obj = $sth->fetchObject('tshirt')) {
      return $obj;
    } else {
      return false;
    }
  }
  
  function getTshirtByParams($dbh, $color = 3, $size = 3, $tournament = 1) {    
    $query = getTshirtSelect().' where tc.id = '.$color.' and tz.id = '.$size.' and te.id = '.$tournament;
    $sth = $dbh->query($query);
    if ($obj = $sth->fetchObject('tshirt')) {
      return $obj;
    } else {
      return false;
    }
  }
  
  function getTshirtsByPlayerId($dbh, $playerId, $tournament = 1) {
    $query = '
      select 
        pt.id as id,
        ts.name as name,
        "tshirt" as class,
        pt.number as number,
        pt.number as number_id,
        pt.person_id as player_id,
        pt.id as playerTshirt_id,
        tc.name as color,
        tc.id as color_id,
        tz.name as size,
        tz.id as size_id,
        tt.id as tournamentTshirt_id,
        te.name as tournamentEdition,
        te.id as tournamentEdition_id
      from personTShirt pt
        left join tournamentTShirt tt on pt.tournamentTShirt_id = tt.id
        left join tshirt ts on tt.tshirt_id = ts.id
        left join tournamentEdition te on tt.tournamentEdition_id = te.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
      where (te.id = '.$tournament.' or te.id is null) and pt.person_id = '.$playerId;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('tshirt')) {
      $objs[] = $obj;
    }
    return $objs;
  }
    
  function getNoOfTshirts($dbh, $id = false) {
    $query = '
      SELECT 
        tt.id as tournamentTshirt_id,
        tt.name as name, 
        ifnull(count(pt.id),0) as buyers, 
        ifnull(sum(pt.number),0) as reserved,
        ifnull(count(pt.dateDelivered),0) as delivered, 
        ifnull(tt.number, 0) as total,
        ifnull(tt.soldOnSite, 0) as soldOnSite
      FROM `tournamentTShirt` tt 
        left join personTShirt pt on pt.tournamentTShirt_id = tt.id
    ';
    $query .= ($id) ? ' where tt.id = '.$id : '';
    $query .= ' group by tt.id';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('tshirt')) {
      $objs[] = $obj;
    }
    return (count($objs) == 1) ? $objs[0] : $objs;
  }
  
  function addTshirt($dbh, $player) {
    return $player->addTshirt($dbh);
  }
  
  function delTshirt($dbh, $id) {
    $tShirt = new tshirt(array('playerTshirt_id' => $id));
    return $tShirt->deleteOrder($dbh);
  }
  
  function updateTshirt($dbh, $id, $tshirt, $number = 1) {
    $tshirt->number = $number;
    $tshirt->playerTshirt_id = $id;
    $tshirt->updateOrder($dbh);
  }
  
  function getCurrentPlayer($dbh, $ulogin) {
    if ($ulogin) {
      $id = getIdFromUser($dbh, $ulogin->Username($_SESSION['uid']));
      $player = ($id) ? getPlayerById($dbh, $id) : false;
      return ($player) ? $player : false;
    } else {
      return false;
    }
  }
    
  function getTshirtForm($dbh, $ulogin, $tournament = 1) {
    $player = getCurrentPlayer($dbh, $ulogin);
    $tshirts = $player->getTshirts($dbh, $tournament);
    if($tshirts && count($tshirts > 0)) {
      $shown = '<p id="tshirtOrderTr">Nedan ser du de tröjor du redan har beställt.</P>';
    } else {
      $shown = '<p id="tshirtNoneSpan" class="italic">Du har inte beställt några tröjor än. '.((__tshirtsDisabled__) ? '' : 'Beställ tröjor nu genom att klicka på plus-tecknet!').'</p>';
    }
    $content = '
        <div id="tshirtOrderDiv">
          <h2 class="entry-title">Tröjbeställningar</h3>
          <input type="hidden" id="tournamentHidden" value="'.$tournament.'">
          <input type="hidden" id="playerIdHidden" value="'.$player->id.'">
          <div id="tshirtOrderTable">
          '.$shown.((__tshirtsDisabled__) ? '<p class="italic">Det går inte längre att beställa tröjor online, men vi kommer att sälja ett begränsat antal tröjor på plats.</p>' : '<p class="italic">Alla ändringar nedan utförs direkt.').'</p>
    ';
    if($tshirts && count($tshirts > 0)) {
      foreach($tshirts as $tshirt) {
        $content .= getTshirtRow($dbh, $tournament, $tshirt->playerTshirt_id, $tshirt);
        $total += $tshirt->number;
        $highest = ($tshirt->playerTshirt_id > $highest) ? $tshirt->playerTshirt_id : $highest;
      }
    } 
    $content .= '
          </div>
          <p style="display: '.((__tshirtsDisabled__) ? 'none' : '').';">Lägg till fler tröjor:<img id="tshirtAdd" src="'.__baseHref__.'/images/add_icon.gif" class="icon" onclick="addTshirt(this);" alt="Klicka för att lägga till fler tröjor" title="Klicka för att lägga till fler tröjor"><span id="tshirtAddSpan" class="errorSpan toolTip"></span></p>
          <br />
          <p><span id="tshirtCostSpan">Totalt pris: SEK '.($total * 100).' kr</span></p>
          <input type="hidden" id="tshirtCostHidden" value="'.($total * 100).'" />
          <input type="hidden" id="tshirtTotalHidden" value="'.$total.'" />
          <input type="hidden" id="tshirtHighestHidden" value="'.$highest.'" />
        </div>
    ';
    return $content;
  }
  
  function getTshirtSizes($dbh, $tournament = 1) {
    $tshirts = getTshirts($dbh, $tournament);
    $sizes = array();
    foreach($tshirts as $tshirt) {
     if (!in_array($tshirt->size, $sizes)) {
        $sizes[$tshirt->size_id] = $tshirt->size;
      }
    }
    return $sizes;
  }

  function getSizeById($dbh, $id) {
    $sizes = getTshirtSizes($dbh);
    foreach ($sizes as $sizeId => $size) {
      if ($sizeId == $id) {
        return (object) array('id' => $sizeId, 'name' => $size);
      }
    }
    return false;
  }

  function getTshirtColors($dbh, $tournament = 1) {
    $tshirts = getTshirts($dbh, $tournament);
    foreach($tshirts as $tshirt) {
     if (!in_array($tshirt->color, $colors)) {
        $colors[$tshirt->color_id] = $tshirt->color;
      }
    }
    return $colors;
  }
  
  function getTshirtRow($dbh, $tournament, $num, $playerTshirt = null, $warning = true, $asJson = false) {
    $tshirts = getTshirts($dbh, $tournament);
    $content = '<div id="'.$num.'_tshirtTr"><p>';
    $json['trId'] = $num.'_tshirtTr';
    $options['size'] = array('0' => 'Välj...');
    $options['color'] = array('0' => 'Välj...');
    foreach($tshirts as $tshirt) {
     if (!in_array($tshirt->size, $options['size'])) {
        $options['size'][$tshirt->size_id] = $tshirt->size;
      }
      if (!in_array($tshirt->color, $options['color'])) {
        $options['color'][$tshirt->color_id] = ucfirst($tshirt->color);
      }
    }
    $options['number'] = array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10);
    foreach(array('number' => 'antal', 'color' => 'färg', 'size' => 'storlek') as $param => $label) {
      $json[$param]['label'] = ucfirst($label).': ';
      $content .= $json[$param]['label'];
      $json[$param]['select'] = '<select id="'.$num.'_tshirt'.ucfirst($param).'Select" class="select '.$param.' tshirtSelect" previous="'.(($playerTshirt && count($playerTshirt) > 0 && $playerTshirt->{$param.'_id'}) ? $playerTshirt->{$param.'_id'} : '0').'" onchange="tshirtChanged(this);"'.((__tshirtsDisabled__) ? ' disabled' : '').'>';
      foreach($options[$param] as $option_id => $option) {
        $json[$param]['select'] .= '<option value="'.$option_id.'"';
        if ($playerTshirt && count($playerTshirt) > 0 && $playerTshirt->{$param.'_id'} == $option_id) {
          $json[$param]['select'] .= ' selected ';
        }
        $json[$param]['select'] .= '>'.$option."</option>\n";
      }
      $json[$param]['select'] .= '</select>';
      $content .= $json[$param]['select'];
    }
    $json[$param]['img'] = (!__tshirtsDisabled__) ? '<img id="'.$num.'_tshirtDel" src="'.__baseHref__.'/images/cancel.png" class="icon" onclick="delTshirt('.$num.');" alt="Click to delete this T-shirt" title="Click to delete this T-shirt"/><span class="error errorSpan" id="'.$num.'_tshirtSpan">' : '';
    if ($warning && $playerTshirt && count($playerTshirt) > 0 && (!($playerTshirt->number_id > 0) || !($playerTshirt->color_id > 0) || !($playerTshirt->size_id > 0))) {
      $json[$param]['img'] .= 'Tröjan är beställd när alla tre valen är ifyllda';
    }
    $json[$param]['img'] .= '</span>';
    $content .= $json[$param]['img'].'</p></div>';
    $json['success'] = true;
    return ($asJson) ? $json : $content;
  }
  
  function addTeam($dbh, $team, $player = null, $method = 'insert into') {
    $query = $method.' team set
        team.name=:name,
        team.initials=:initials,
        team.national=:national,
        team.country_id=:country_id,
        team.contactPlayer_id=(
          select pl.id from player pl
          where pl.person_id=:contactPlayer_id
          and pl.tournamentDivision_id = 1
        ),
        team.tournamentDivision_id=:tournamentDivision_id,
        team.dateRegistered=:dateRegistered,
        team.registerPerson_id=:registerPerson_id
    ';
    if ($method == 'update') {
      $query .= ' where id = :teamId';
      $insert[':teamId'] = $team->id;
    }
    $insert[':name'] = $team->name;
    $insert[':initials'] = $team->initials;
    $insert[':national'] = $team->national;
    $insert[':country_id'] = $team->country_id;
    $insert[':contactPlayer_id'] = $team->contactPlayer_id;
    $insert[':tournamentDivision_id'] = ($team->tournamentDivision_id) ? $team->tournamentDivision_id : 3;
    $insert[':dateRegistered'] = ($team->dateRegistered) ? $team->dateRegistered : date('Y-m-d');
    $insert[':registerPerson_id'] = $team->registerPerson_id;
    $sth = $dbh->prepare($query);
    $result = $sth->execute($insert);
    if ($method == 'insert into') {
      $team->id = $dbh->lastInsertId();
      if ($player) {
        $team->addPlayer($dbh, $player);
      }
    }
    deNorm($dbh, 'team');
    return $team->id;
  }
  
  function editTeam($dbh, $team, $players = null) {
    if ($players) {
      $team->removePlayers($dbh);
      $team->addPlayers($dbh, $players);
    }
    return addTeam($dbh, $team, null, 'update');
  }
      
  function getTeamSelect() {
    return '
      select 
        tm.id as id,
        tm.name as name,
        "team" as class,
        tm.qualGroup_id as qualGroup_id,
        tm.place as place,
        tm.here as here,
        tm.hereFinal as hereFinal,
        tm.qualPlace as qualPlace,
        tm.initials as initials,
        tm.national as national,
        tm.registerPerson_id as registerPerson_id,
        tm.contactPlayer_id as contactPlayer_id,
        tm.contactPlayer_name as contactPlayer_name,
        tm.country as country,
        tm.country_id as country_id,
        td.name as tournamentDivision,
        td.id as tournamentDivision_id,
        te.name as tournamentEdition,
        te.id as tournamentEdition_id
      from team tm
        left join tournamentDivision td on tm.tournamentDivision_id = td.id
        left join tournamentEdition te on td.tournamentEdition_id = te.id
    ';
  }  
    
  function getTeams($dbh, $where = ' where tm.tournamentDivision_id = 3 ', $national = false) {
    $query = getTeamSelect().' '.$where;
    $query .= ($national) ? (($where) ? ' and ' : ' where ').' tm.national = 1 ' : (($where) ? ' and ' : ' where ').' (tm.national is null or tm.national != 1) ';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('team')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getTeamById($dbh, $teamId) {
    $query = getTeamSelect().' where tm.id = '.$teamId;
    $sth = $dbh->query($query);
    if ($obj = $sth->fetchObject('team')) {
      return $obj;
    } else {
      return false;
    }
  }
      
  function getTeamByCountry($dbh, $countryId, $national = true) {
    $query = getTeamSelect().' where tm.country_id = '.$countryId;
    $query .= ($national) ? ' and tm.national = 1' : ' and (tm.national is null or tm.national != 1)';
    $sth = $dbh->query($query);
    if ($obj = $sth->fetchObject('team')) {
      return $obj;
    } else {
      return false;
    }
  }
  
  function getFreeTeamMembers($dbh, $tournament = 1) {
    $query = getPlayerSelect();
    $query .= '
      left join teamPlayer tp on tp.player_id = m.id
      where tp.id is null and m.tournamentEdition_id = '.$tournament.'
      order by p.firstName, p.lastName';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('player')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getTeamForm($dbh, $ulogin, $tournament = 1) {
    $player = getCurrentPlayer($dbh, $ulogin);
    $content = submenu2($dbh, $ulogin, 'anmalda', false, $player);
    $content .= '<p>I dubbel spelar ni med varsin flipper. Exakta regler hittas <a href="'.__baseHref__.'/?s=dubbel">här</a></p>';    $players = (array) getFreeTeamMembers($dbh, $tournament);
    $team = $player->getTeam($dbh);
    if ($team) {
      $regTeamDisplay = 'none';
      $editTeamDisplay = '';
      $teamMembers = $team->getMembers($dbh);
      $players = array_merge($teamMembers, $players);
    } else {
      $team = new team(array('id' => 0, 'name' => '', 'initials' => ''));
      $regTeamDisplay = '';
      $editTeamDisplay = 'none';
    }
    $content .= '
      <p id="regTeamHeader" class="regTeam" style="display: '.$regTeamDisplay.';"><b>Du är inte medlem i något lag.</b> Om du ska vara medlem i ett lag som redan är anmält, be din medspelare att stoppa in dig i laget. Om du vill anmäla ett nytt lag så fyller du fälten nedan.</p>
      <p id="editTeamHeader" class="editTeam" style="display: '.$editTeamDisplay.';">Du är medlem i nedanstående lag, och har rätt att ändra uppgifterna. <span class="italic">Använd knappen för att ändra namn eller TAG. Ändrar du medlem eller kapten så ändras det direkt.</span></p>
      <p class="italic" class="editTeam" style="display: '.$editTeamDisplay.';">OBS: Du kan inte lägga till spelare som redan är medlem i ett annat lag, de måste gå ur det andra laget först.</p>
    ';
    $content .= getUploadForm($dbh, $team, (($editTeamDisplay == 'none') ? false : true ));
    $content .= '
      <form id="newData" name="newData" action="'.$_SERVER['REQUEST_URI'].'">
        <input type="hidden" name="loggedIn" id="loggedIn" value="true">
        <input type="hidden" name="newPhoto" id="newPhoto" value="false">
        <input type="hidden" name="baseHref" id="baseHref" value="'.__baseHref__.'">
        <input type="hidden" name="user" id="user" value="'.$player->username.'">
        <input name="class" id="classHidden" type="hidden" value="team">
        <input name="id" id="idHidden" type="hidden" value="'.$team->id.'">
        <input name="action" id="action" type="hidden" value="regTeam">
        <input name="dateRegistered" id="dateRegisteredHidden" type="hidden" value="'.date('Y-m-d').'">
        <input name="registerPerson_id" id="registerPerson_idHidden" type="hidden" value="'.$player->id.'">
        <input name="tournamentDivision_id" id="tournamentDivision_idHidden" type="hidden" value="3">
        <div id="regTeamTable">
          <h3>Laganmälan</h3>
          <div id="nameTr">
            <label id="nameLabel" for="nameText">Lagnamn</label>
            <input name="name" id="nameText" type="text" class=" mandatory" onkeyup="checkField(this);" value="'.$team->name.'"><span id="nameSpan" class=" errorSpan">*</span>
          </div>
          <div id="initialsTr">
            <label id="initialsLabel" for="initialsText">Lag-TAG</label>
            <input name="initials" id="initialsText" type="text" onkeyup="checkField(this);" value="'.$team->initials.'"><span id="initialsSpan" class=" errorSpan toolTip"></span>
          </div>
          <div>
            <input id="submit" type="button" value="Skicka!" class="formInput" onclick="regTeam()" disabled><input id="delete" type="button" value="Ta bort laget!" class="formInput editTeam" onclick="deleteTeam(\'submitSpan\')" style="display: '.$editTeamDisplay.'"><span id="submitSpan" class=" errorSpan toolTip" style="display: none;"></span>
          </div>
    ';
    $playerNum = array(1,2);
    foreach($playerNum as $num) {
      $checked = '';
      $disabled = '';
      $leave = 'none';
      $captain = ' disabled';
      $captainDisplay = 'none';
      $selected = 0;
      if ($team->id == 0 && $num == 1) {
        $checked = ' checked';
        $captain = '';
        $captainDisplay = '';
      } else if ($teamMembers[$num - 1]) {
        $selected = $teamMembers[$num - 1]->id;
        $captain = '';
        if ($teamMembers[$num - 1]->mainPlayerId == $team->contactPlayer_id) {
          $checked = ' checked';
          $captainDisplay = '';
        }
        if ($teamMembers[$num - 1]->id == $player->id) {
          $disabled = ' disabled';
          $leave = '';
        }
      }
      if ($selected == 0) {
        $incomplete = '';
      } else {
        $incomplete = 'none';
      }
      $content .= '
          <div id="teamPlayer'.$num.'Tr" style="display: '.$editTeamDisplay.';" class="editTeam">
            <label id="teamPlayer'.$num.'Label" for="teamPlayer'.$num.'">Spelare #'.$num.'</label>
            '.createSelect($players, 'teamPlayer'.$num.'Select', $selected, 'memberSelected', $disabled).'<input type="radio" name="contactPlayer_id" id="contactPlayer_id'.$num.'" value="'.$selected.'" onchange="setCaptain();"'.$checked.$captain.'><span id="contactPlayer_id'.$num.'Captain" style="display: '.$captainDisplay.';">&nbsp;&nbsp;Kapten</span><span id="teamPlayer'.$num.'Span" class=" errorSpan toolTip" style="display: none;"></span><span id="teamIncomplete'.$num.'Span" class="teamIncomplete errorSpan toolTip" style="display: '.$incomplete.';">Laget är inte komplett</span>
          </div>
      ';
    }
    
    $content .= '
          <div class="editTeam" style="display: '.$editTeamDisplay.'">
            <input id="leaveTeam" type="button" value="Gå ur laget!" class="editTeam formInput" onclick="removeTeamMember('.$player->id.', \'leaveSpan\');"><br /><span id="leaveSpan" class="editTeam italic errorSpan">OBS: Går du ur kan du bara gå med igen med hjälp av din medspelare.</span>
          </div>
        </div>
      </form>
    ';
    return $content;
  }
  
  function getUploadForm($dbh, $obj, $display = true, $button = true) {
    $content = '
      <script src="'.__baseHref__.'/js/contrib/jquery.form.min.js" type="text/javascript"></script>
      <form id="imageForm" method="post" enctype="multipart/form-data" action="'.__baseHref__.'/ajax/imageUpload.php?obj='.$obj->class.'&id='.$obj->id.'" style="display: '.(($display) ? '' : 'none').';" class="edit'.ucfirst($obj->class).'">
        <h2 colspan="2" id="reg'.ucfirst($obj->class).'ImgH2">Ändra foto/logga</h2>
  	    <div id="preview">
  		    <img src="'.$obj->getPhoto(true).'" id="thumb" class="preview" alt="Förhandsbild av '.$obj->name.'">
          <div id="imageLoader"></div>
  	    </div>
  	    <div id="uploadForm">
          <label id="imageUploadLabel" class="italic">Klicka på bilden för att byta bild (spara med skicka-knappen)</label>
          <input type="file" name="imageUpload" id="imageUpload">
        </div>
        <input id="submitImg" type="button" value="Skicka upp bilden!" class="formInput" onclick="'.$obj->class.'Photo();" style="display: '.(($button) ? '' : 'none').'" disabled><span id="submitImgSpan" class=" errorSpan" style="display: none;"></span>
        <script type="text/javascript">
          $(document).ready(function() { 
            $(\'#imageUpload\').on(\'change\', function() {
              $(\'#preview\').html(\'\');
              $(\'#imageLoader\').html(\'<img src="'.__baseHref__.'/images/loader.gif" alt="Uploading...."/>\');
              $(\'#submitImg\').prop(\'disabled\', false);
              $(\'#imageForm\').ajaxForm({
                target: \'#preview\'
              }).submit();
              $(\'#imageLoader\').html(\'\');
            });
            $(\'#thumb\').on(\'click\', function() {
              $(\'#imageUpload\').trigger(\'click\');
            });
          }); 
        </script>
      </form>
    ';
    return $content;
  }
  
  function getCurCalcForm($cur = 'all', $id = false, $action = 'curCalc', $selected = 0) {
    $id = ($id) ? $id : $cur;
    switch ($cur) {
      case 'SEK':
        $max = 1500;
        $step = 100;
      break;
      case 'EUR':
        $max = 190;
        $step = 12.5;
      break;
      case 'GBP':
        $max = 150;
        $step = 10;
      break;
      case 'USD':
        $max = 255;
        $step = 16.67;
      break;
      case 'all':
        $content = '<div class="curCalc"><h2 class="bold">Currency calculator</h2>';
        foreach (array('SEK', 'EUR', 'GBP', 'USD') as $cur) {
          $content .= getCurCalcForm($cur);
        }
        return $content.'<span class="errorSpan italic">Note: The calculator is approximate, and our exchange rates are bad.</span></div>';
      break;
      default:
        return false;
      break;
    }
    $content = '<label>'.$cur.':</label>';
    $content .= '<select id="'.$id.'" onchange="'.$action.'(this);" class="curCalc">';
    for ($i = 0; $i <= $max; $i = $i + $step) {
      $content .= '<option value="'.round($i).'"';
      $content .= ($selected == $i) ? ' selected' : '';
      $content .= '>'.round($i).'</option>';
    }
    return $content.'</select>';
  }  
  
  function getQualGroupSelect($alias = 'q', $extraCols = false) {
    $query = '
      select 
        '.$alias.'.id as id,
        concat(q.date, " ", left('.$alias.'.startTime, 5), "-", left('.$alias.'.endTime, 5)) as fullName,
        concat('.$alias.'.name, " (", left('.$alias.'.startTime, 5), "-", left('.$alias.'.endTime, 5), ")") as name,
        '.$alias.'.name as shortName,
        '.$alias.'.date as date,
        left('.$alias.'.startTime, 5) as startTime,
        left('.$alias.'.endTime, 5) as endTime,
        "qualGroup" as class,
        '.$alias.'.comment as comment,
        '.$alias.'.tournamentDivision_id as tournamentDivision_id
    ';
    $query .= ($extraCols) ? ', '.$extraCols : '';
    $query .= '
      from qualGroup '.$alias.'
    ';
    return $query;
  }

  function getQualGroupById($dbh, $id) {
    $query = getQualGroupSelect().' where q.id = '.$id;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('qualGroup')) {
      return $obj;
    }
  }
  
  function getQualGroupsByDivision($dbh, $division = 1) {
    $query = getQualGroupSelect('q', 'td.tournamentEdition_id as tournamentEdition_id').'
      left join tournamentDivision td
        on q.tournamentDivision_id = td.id
      where td.id = '.$division.' order by q.date, q.startTime
      ';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('qualGroup')) {
      $objs[] = $obj;
    }
    return $objs;    
  }
  
  function getQualGroups($dbh, $tournament = 1) {
    $query = getQualGroupSelect().'
      left join tournamentDivision td
        on q.tournamentDivision_id = td.id
      where (td.tournamentEdition_id = '.$tournament.' or td.tournamentEdition_id is null) order by q.date
      ';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('qualGroup')) {
      $objs[] = $obj;
    }
    return $objs;
  }
    
  function getQualGroupForm($dbh, $player, $tournament = 1) {
    $qualGroups = getQualGroups($dbh, $tournament);
    if ($player) {
      $playerQualGroups = $player->getQualGroups($dbh, $tournament);
      $playerPreferedQualGroups = $player->getPreferedQualGroup($dbh, $tournament);
      $playerQualGroupIds = [];
      if($playerQualGroups) {
        foreach($playerQualGroups as $playerQualGroup) {
          $playerQualGroupIds[] = $playerQualGroup->id;
        }
      }
      if($playerPreferedQualGroups) {
        foreach($playerPreferedQualGroups as $playerPreferedQualGroup) {
          $playerPreferedQualGroupIds[] = $playerPreferedQualGroup->id;
        }
      }
      if($qualGroups) {
        $tournamentDivisionIds = [];
        foreach($qualGroups as $qualGroup) {
          if (!in_array($qualGroup->tournamentDivision_id, $tournamentDivisionIds)) {
            $tournamentDivisionIds[] = $qualGroup->tournamentDivision_id;
            $qualGroupsByDiv[$qualGroup->tournamentDivision_id] = [];
          }
          array_push($qualGroupsByDiv[$qualGroup->tournamentDivision_id], $qualGroup);    
        }
      }
    }
    $prefered = ($playerPreferedQualGroup) ? 'checked' : '';
    $checked = ($playerQualGroups) ? 'checked' : '';
    $qualLimit[1] = __mainQualLimit__;
    $qualLimit[2] = __classicsQualLimit__;
    $choice = ($_REQUEST['active']) ? false : false;
    $content = '
          <div id="qualGroupDiv">
            <h2 class="entry-title">'.(($choice) ? 'Välj dina' : 'Ansök om att byta').' kvaltider här</h2>
            <p class="italic">'.(($choice) ? '<input type="radio" '.$prefered.' id="qualGroupRadioExample">&nbsp;&nbsp;Använd radioknapparna för att göra dina förstahandsval - en per division.<br/>
            <input type="checkbox" '.$checked.' id="qualGroupCheckboxExample">&nbsp;&nbsp;Använd checkboxarna för att välja övriga kvaltider som passar dig.<br />' : '').'
            Numret efter respektive kvaltid visar antal spelare '.(($choice) ? 'med kvaltiden som förstahandsval' : 'som har blivit tilldelade den kvaltiden').'. Max är '.__mainQualLimit__.' ('.__classicsQualLimit__.' i Classics), och det går inte att välja tider som redan är fulla.<br />
            Om kvaltiderna för Classics är utgråade, så beror det på att du inte har anmält dig i Classics. Gör det genom att klicka i Classics <a href="'.__baseHref__.'/?s=edit">här</a>.</p>
            <input type="hidden" id="tournamentHidden" value="'.$tournament.'">
            <input type="hidden" id="idHidden" value="'.$player->id.'">
            <input type="hidden" id="choiceHidden" value="'.(($choice) ? '1' : '0').'">
    ';
    $content .= '
          </div>
          <div id="qualGroupTableDiv" class="periodTable">
            '.(($choice) ? '<p>Klicka i/ur alla tillgängliga kvaltider: <input type="checkbox" id="qualGroupChackAll" onclick="qualGroupCheckAll(this);"' : '');
    if ($choice && $playerQualGroups && count($playerQualGroups) == count($qualGroups)) {
      $content .= ' checked';
    }            
    $content .= ($choice) ? '></p>' : '';
    if($qualGroups && count($qualGroups > 0)) {
      foreach($tournamentDivisionIds as $tournamentDivisionId) {
        $type = ($tournamentDivisionId == 1) ? 'main' : 'classics';
        foreach($qualGroupsByDiv[$tournamentDivisionId] as $qualGroup) {
          $disabled = ($player->{$type} && ($choice || (!$choice && $qualGroup->getNoOfAssignedPlayers($dbh) < $qualLimit[$tournamentDivisionId]))) ? false : true;
          if (!($date) || $date != $qualGroup->date) {
            if ($date) {
              $content .= '</div>';
            }
            $date = $qualGroup->date;
            $content .= '
            <div id="qualGroupMain'.$date.'Table" class="qualGroupTable">
              <div>
                '.(($choice) ? '<input type="checkbox" id="'.$date.'_'.$type.'Checkbox" onchange="qualGroupCheckAll(this, \''.$tournamentDivisionId.'_'.$date.'\');" class="qualGroupCheckbox qualGroupDate '.$qualGroup->date.'"'.(($disabled) ? ' disabled ' : '').'>' : '').'
                <b>'.$date.' ('.ucfirst($type).')</b>
              </div>
            ';
          }
          $checked = (in_array($qualGroup->id, $playerQualGroupIds)) ? true : false;
          $prefered = ($playerPreferedQualGroups && in_array($qualGroup->id, $playerPreferedQualGroupIds)) ? true : false;
          $bold = (($tournamentDivisionId == 1 && $player->mainQualGroup_id == $qualGroup->id) || ($tournamentDivisionId == 2 && $player->classicsQualGroup_id == $qualGroup->id)) ? true : false;
          $content .= getQualGroupRow($dbh, $qualGroup, $checked, $prefered, $disabled, $bold);
        }
      } 
      $content .= '
            </div>
          </div>

      ';
    }
    return $content;
  }
    
  function getQualGroupRow($dbh, $qualGroup = null, $checked = false, $prefered = false, $disabled = false, $bold=false) {
    return getTimeSlotRow($dbh, 'qualGroup', $qualGroup, $checked, $prefered, $disabled, $bold);
  }  
  
  function addVolunteerTask($dbh, $volunteer, $task, $tournamentId = 1) {
    return addVolunteerItem($dbh, $volunteer, $task, 'task', $tournamentId);    
  }
  
  function addVolunteerPeriod($dbh, $volunteer, $period, $tournamentId = 1) {
    return addVolunteerItem($dbh, $volunteer, $period, 'period', $tournamentId);
  }
  
  function addVolunteerItem($dbh, $volunteer, $item, $type = 'task', $tournamentId = 1) {
    $query = '
      insert into 
        volunteer'.ucfirst($type).'
      set 
        volunteer_id = :volunteerId,
        '.$type.'_id = :'.$type.'Id,
        tournamentEdition_id = :tournamentId,
        name = :name
      on duplicate key update
        volunteer_id = :volunteerId,
        '.$type.'_id = :'.$type.'Id,
        tournamentEdition_id = :tournamentId,
        name = :name
    ';
    $update = array(
      ':volunteerId' => $volunteer->volunteer_id,
      ':'.$type.'Id' => $item->id,
      ':tournamentId' => $tournamentId,
      ':name' => $volunteer->lastName.': '.(($type == 'period') ? $item->fullName : $item->name)
    );
    $sth = $dbh->prepare($query);
    if ($sth->execute($update)) {
      return $dbh->lastInsertId();
    } else {
      return false;
    }    
  }
  
  function delVolunteerTask($dbh, $volunteer, $task, $tournamentId = 1) {
    return delVolunteerItem($dbh, $volunteer, $task, 'task', $tournamentId);
  }

  function delVolunteerPeriod($dbh, $volunteer, $period, $tournamentId = 1) {
    return delVolunteerItem($dbh, $volunteer, $period, 'period', $tournamentId);
  }
  
  function delVolunteerItem($dbh, $volunteer, $item, $type = 'task', $tournamentId = 1) {
    $query = '
      delete 
        from volunteer'.ucfirst($type).'
      where
        volunteer_id = :volunteerId and
        '.$type.'_id = :'.$type.'Id and
        tournamentEdition_id = :tournamentId
    ';
    $delete = array (
      ':volunteerId' => $volunteer->volunteer_id,
      ':'.$type.'Id' => $item->id,
      ':tournamentId' => $tournamentId
    );
    $sth = $dbh->prepare($query);
    if ($sth->execute($delete)) {
      return true;
    } else {
      return false;
    }    
  }

  function getTasksByPlayerId($dbh, $playerId, $tournament = 1) {
    $query = '
      select 
        t.id as id,
        t.name as name,
        t.acronym as shortName,
        "task" as class,
        v.person_id as player_id,
        v.id as volunteer_id,
        vt.id as volunteerTask_id,
        t.comment as comment,
        t.tournamentEdition_id as tournamentEdition_id
      from volunteerTask vt
        left join task t on vt.task_id = t.id
        left join volunteer v on vt.volunteer_id = v.id
      where (t.tournamentEdition_id = '.$tournament.' or t.tournamentEdition_id is null) and v.person_id = '.$playerId;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('task')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getPeriodsByPlayerId($dbh, $playerId, $tournament = 1) {
    $query = '
      select 
        p.id as id,
        concat(p.date, " ", left(p.startTime, 5), "-", left(p.endTime, 5)) as fullName,
        concat(left(p.startTime, 5), "-", left(p.endTime, 5)) as name,
        p.date as date,
        left(p.startTime, 5) as startTime,
        left(p.endTime, 5) as endTime,
        "period" as class,
        v.person_id as player_id,
        v.id as volunteer_id,
        vp.id as volunteerPeriod_id,
        p.comment as comment,
        p.tournamentEdition_id as tournamentEdition_id
      from volunteerPeriod vp
        left join period p on vp.period_id = p.id
        left join volunteer v on vp.volunteer_id = v.id
      where (p.tournamentEdition_id = '.$tournament.' or p.tournamentEdition_id is null) and v.person_id = '.$playerId.'
      order by p.date, p.startTime';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('period')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getTaskById($dbh, $id) {
    $query = '
      select 
        t.id as id,
        t.name as name,
        t.acronym as shortName,
        "task" as class,
        t.comment as comment,
        t.tournamentEdition_id as tournamentEdition_id
      from task t
      where t.id = '.$id;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('task')) {
      return $obj;
    }
  }
  
  function getPeriodById($dbh, $id) {
    $query = '
      select 
        p.id as id,
        concat(p.date, " ", left(p.startTime, 5), "-", left(p.endTime, 5)) as fullName,
        concat(left(p.startTime, 5), "-", left(p.endTime, 5)) as name,
        p.date as date,
        left(p.startTime, 5) as startTime,
        left(p.endTime, 5) as endTime,
        "period" as class,
        p.comment as comment,
        p.tournamentEdition_id as tournamentEdition_id
      from period p
      where p.id = '.$id;
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('period')) {
      return $obj;
    }
  }

  function getTasks($dbh, $tournament = 1) {
    $query = '
      select 
        t.id as id,
        t.name as name,
        t.acronym as shortName,
        "task" as class,
        t.comment as comment,
        t.tournamentEdition_id as tournamentEdition_id
      from task t
      where (t.tournamentEdition_id = '.$tournament.' or t.tournamentEdition_id is null)';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('task')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getPeriods($dbh, $tournament = 1) {
    $query = '
      select 
        p.id as id,
        concat(p.date, " ", left(p.startTime, 5), "-", left(p.endTime, 5)) as fullName,
        concat(left(p.startTime, 5), "-", left(p.endTime, 5)) as name,
        p.date as date,
        left(p.startTime, 5) as startTime,
        left(p.endTime, 5) as endTime,
        TIMEDIFF(p.endTime,p.startTime) as length,
        "period" as class,
        p.comment as comment,
        p.tournamentEdition_id as tournamentEdition_id
      from period p
      where (p.tournamentEdition_id = '.$tournament.' or p.tournamentEdition_id is null)
      order by p.date, p.startTime';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('period')) {
      $objs[] = $obj;
    }
    if (count($objs) > 0) {
      return $objs;
    } else {
      return false;
    }
  }
    
  function getVolunteerById($dbh, $id) {
    $player = getPlayerById($dbh, $id);
    $query = '
      select 
        id as volunteer_id,
        coalesce(v.hours, 0) as hours
      from volunteer v
      where v.person_id = '.$id;  
    $sth = $dbh->query($query);
    if ($obj = $sth->fetchObject()) {
      $player->hours = $obj->hours;
      $player->volunteer_id = $obj->volunteer_id;
      return $player;
    } else {
      return false;
    }
  }
  
  function getVolunteerForm($dbh, $ulogin, $tournament = 1) {
    $volunteer = getCurrentPlayer($dbh, $ulogin);
    $tasks = getTasks($dbh, $tournament);
    $periods = getPeriods($dbh, $tournament);
    $hours = 0;
    if ($volunteer) {
      $hours = $volunteer->hours;
      $volunteerTasks = getTasksByPlayerId($dbh, $volunteer->id, $tournament);
      $volunteerPeriods = getPeriodsByPlayerId($dbh, $volunteer->id, $tournament);
      $volunteerTaskIds = [];
      if($volunteerTasks) {
        foreach($volunteerTasks as $volunteerTask) {
          $volunteerTaskIds[] = $volunteerTask->id;
        }
      }
      $volunteerPeriodIds = [];
      if($volunteerPeriods) {
        foreach($volunteerPeriods as $volunteerPeriod) {
          $volunteerPeriodIds[] = $volunteerPeriod->id;
        }
      }
    }
    if(($tasks && count($tasks > 0)) || ($periods && count($periods > 0))) {
      $shown = 'Any info already shown is what you have already registered.<br />';
    } else {
      $shown = '<span id="volunteerNoneSpan" class="italic">You have nothing previsously registered.<br /></span>';
    }
    $content = '
          <div id="volunteerRegDiv">
            <h2 class="entry-title">Volunteer registration form</h3>
            <input type="hidden" id="tournamentHidden" value="'.$tournament.'">
            <input type="hidden" id="volunteerIdHidden" value="'.$volunteer->volunteer_id.'">
            <span class="italic">'.$shown.'The total number of hours does not have to match the number of hours you select as periods. We will distribute the number of hours you specify over the periods you are available, and we will not assign more hours than you offer.<br />Changes below will take effect immediately.</span>
            <br /><br />
            <p>Total number of hours you are willing to contribute: <select id="volunteerHoursSelect" class="select" onchange="volunteerHoursChanged(this);">
    ';
    for($i = 0; $i <= 100; $i++) {
      $content .= '<option value="'.$i.'"';
      if ($i == $hours) {
        $content .= ' selected';
      }
      $content .= '>'.$i.'</option>'."\n";
    }
    $content .= '</select><span class="error errorSpan toolTip" id="volunteerHoursSpan"></span></p>
          </div>
          <div id="periodTableDiv" class="periodTable">
            <h3>Periods: Check/uncheck all: <input type="checkbox" id="periodChackAll" onclick="periodCheckAll(this);" ';
    if ($volunteerPeriods && count($volunteerPeriods) == count($periods)) {
      $content .= 'checked';
    }            
    $content .= '></h2>
    ';
    if($periods && count($periods > 0)) {
      foreach($periods as $period) {
        if (!($date) || $date != $period->date) {
          if ($date) {
            $content .= '</table>';
          }
          $date = $period->date;
          $content .= '
            <table id="period'.$date.'Table" class="periodTable">
              <tr>
                <td><input type="checkbox" id="'.$date.'_'.$type.'Checkbox" onchange="periodCheckAll(this, \''.$date.'\');" class="periodCheckbox periodDate '.$period->date.'"> </td>
                <td><h2><b>'.$date.'</b></h2></td>
              </tr>
          ';
        }
        $content .= getVolunteerRow($dbh, 'period', $period, (($volunteerPeriodIds && in_array($period->id, $volunteerPeriodIds)) ? true : false));
      }
    } 
    $content .= '
            </table>
          </div>
          <div id="taskTableDiv">
            <h2>Tasks:</h2></td>
            <table id="taskTable">
    ';
    if($tasks && count($tasks > 0)) {
      foreach($tasks as $task) {
        $content .= getVolunteerRow($dbh, 'task', $task, (($volunteerTaskIds && in_array($task->id, $volunteerTaskIds)) ? true : false));
      }
    }
    $content .= '
          </table>
        </div>
    ';
    return $content;
  }

  function getVolunteerRow($dbh, $type, $item = null, $checked = false) {
    return getTimeSlotRow($dbh, $type, $item, $checked);
  }
  
  function getTimeSlotRow($dbh, $type, $item = null, $checked = false, $prefered = false, $disabled = false, $bold = false) {
    $choice = ($_REQUEST['active']) ? false : false;
    $content = '<div id="'.$item->id.'_'.$type.'Div"'.(($bold) ? ' class="bold"' : '').'>';
    $content .= ($type == 'task') ? ucfirst($item->name) : '';
    $content .= ($type != 'qualGroup' || $choice) ? '<input type="checkbox" id="'.$item->id.'_'.$type.'Checkbox" onchange="timeSlotChanged(this, \''.$type.'\', '.$item->id.');" class="'.$type.'Checkbox '.(($type == 'qualGroup') ? $item->tournamentDivision_id.'_' : '').$item->date.'" ' : '';
    $content .= ($checked && ($type != 'qualGroup' || $choice)) ? ' checked ' : '';
    $content .= ($disabled && ($type != 'qualGroup' || $choice)) ? ' disabled ' : '';
    $content .= ($type != 'qualGroup' || $choice) ? '>' : '';
    $content .= ($type == 'qualGroup') ? '<input type="radio" id="'.$item->id.'_'.$type.'Radio" name="'.$type.'Div'.$item->tournamentDivision_id.'Radio" onchange="timeSlotPreferedChanged(this, '.$item->id.')" class="'.$type.'Radio '.$item->date.'" '.(($prefered) ? ' checked ' : '').(($disabled) ? ' disabled ' : '').'>' : '';
    $content .= '<span class="error errorSpan toolTip qualGroupSpan" id="'.$item->id.'_'.$type.'Span"></span>';
    $content .= ($item->comment) ? '<span class="italic">'.$item->comment.'</span>' : '';
    $content .= ($type == 'period' || $type == 'qualGroup') ? ucfirst($item->name) : '';
    $content .= ($type == 'qualGroup') ? ': '.(($choice) ? $item->getNoOfPlayers($dbh, true) : $item->getNoOfAssignedPlayers($dbh)) : '';
    $content .= '</div>';
    return $content;
  }
      
  // Validate an email address. Returns true if the email address has the email address format and the domain exists.
  function validEmail($email) {
    $isValid = true;
    $atIndex = strrpos($email, "@"); // Let's split it to make life easier
    if (is_bool($atIndex) && !$atIndex) {
      $isValid = false; // No @!
    } else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64) {
        $isValid = false; // Local part length exceeded
      } else if ($domainLen < 1 || $domainLen > 255) {
        $isValid = false; // Domain part length exceeded
      } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
        $isValid = false; // Local part starts or ends with '.'
      } else if (preg_match('/\\.\\./', $local)) {
        $isValid = false; // Local part has two consecutive dots
      } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
        $isValid = false; // Character not valid in domain part
      } else if (preg_match('/\\.\\./', $domain)) {
        $isValid = false; // Domain part has two consecutive dots
      } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
        if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
          $isValid = false;// Character not valid in local part unless local part is quoted
        }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
        $isValid = false; // domain not found in DNS
      }
    }
    return $isValid;
  }
  
  function deNorm($dbh, $class = false) {
    global $classes;
    if ($class) {
      $updates[] = deNormClass($dbh, $classes->{$class});
    } else {
      $updates = [];
      foreach ($classes as $class) {
        array_push($updates, deNormClass($dbh, $class));
      }
    }
    return true;
  }
  
  function deNormClass($dbh, $class) {
    global $classes;
    if ($classes->{$class->name}->parents) {
      if ($classes->{$class->name}->selfParent) {
        $updates[] = deNormHelper($class->name, $class->name, true);
      }
      foreach($classes->{$class->name}->parents as $parent) {
        if ($classes->{$parent}->parents) {
          $updates[] = deNormHelper($class->name, $parent);
        }
        if ($classes->{$parent}->selfParent) {
          $updates[] = deNormHelper($class->name, $parent, true);        
        }
      }
    }
    if ($classes->{$class->name}->id2name) {
      $num = 1;
      $update = 'update '.$class->name." t1 \n";
      $join = '';
      unset($set);
      foreach($classes->{$class->name}->id2name as $parent) {
        $num++;
        $join .= ' left join '.$parent.' t'.$num." \n";
        $join .= ' on t1.'.$parent.'_id = t'.$num.".id\n";
        $set[] = ' t1.'.$parent.' = t'.$num.".name\n";
        if ($classes->{$parent}->selfParent) {
          $num++;
          $join .= ' left join '.$parent.' t'.$num." \n";
          $join .= ' on t1.parent'.ucfirst($parent).'_id = t'.$num.".id\n";
          $set[] = ' t1.parent'.ucfirst($parent).' = t'.$num.".name\n";
        }
      }
      if ($classes->{$class->name}->selfParent) {
        $num++;
        $join .= ' left join '.$class->name.' t'.$num." \n";
        $join .= ' on t1.parent'.ucfirst($class->name).'_id = t'.$num.".id\n";
        $set[] = ' t1.parent'.ucfirst($class->name).' = t'.$num.".name\n";
      }
      $sets = implode($set, ',');
      $updates[] = $update.$join.' set '.$sets;
    }
    switch ($class->name) {
      case 'team':
        $updates[] = '
          update team t
            left join player pl
              on t.contactPlayer_id = pl.id
            set
              t.contactPlayer_name = concat(ifnull(pl.firstName,""), " ", ifnull(pl.lastName,""))
        ';
      break;
      case 'player':
        $updates[] = '
          update player pl
            left join person p
              on pl.person_id = p.id
            set
              pl.firstName = coalesce(pl.firstName, p.firstName),
              pl.lastName = coalesce(pl.lastName, p.lastName),
              pl.initials = coalesce(pl.initials, p.initials),
              pl.streetAddress = coalesce(pl.streetAddress, p.streetAddress),
              pl.zipCode = coalesce(pl.zipCode, p.zipCode),
              pl.telephoneNumber = coalesce(pl.telephoneNumber, p.telephoneNumber),
              pl.mobileNumber = coalesce(pl.mobileNumber, p.mobileNumber),
              pl.mailAddress = coalesce(pl.mailAddress, p.mailAddress),
              pl.birthDate = coalesce(pl.birthDate, p.birthDate),
              pl.ifpa_id = p.ifpa_id,
              pl.ifpaRank = p.ifpaRank
        ';
        $updates[] = '
          update person p
            left join player pl
              on pl.person_id = p.id
            set
              p.firstName = coalesce(p.firstName, pl.firstName),
              p.lastName = coalesce(p.lastName, pl.lastName),
              p.initials = coalesce(p.initials, pl.initials),
              p.streetAddress = coalesce(p.streetAddress, pl.streetAddress),
              p.zipCode = coalesce(p.zipCode, pl.zipCode),
              p.telephoneNumber = coalesce(p.telephoneNumber, pl.telephoneNumber),
              p.mobileNumber = coalesce(p.mobileNumber, pl.mobileNumber),
              p.mailAddress = coalesce(p.mailAddress, pl.mailAddress),
              p.birthDate = coalesce(p.birthDate, pl.birthDate),
              p.gender_id = coalesce(p.gender_id, pl.gender_id),
              p.gender = coalesce(p.gender, pl.gender),
              p.city_id = coalesce(p.city_id, pl.city_id),
              p.city = coalesce(p.city, pl.city),
              p.region_id = coalesce(p.region_id, pl.region_id),
              p.region = coalesce(p.region, pl.region),
              p.parentRegion_id = coalesce(p.parentRegion_id, pl.parentRegion_id),
              p.parentRegion = coalesce(p.parentRegion, pl.parentRegion),
              p.country_id = coalesce(p.country_id, pl.country_id),
              p.country = coalesce(p.country, pl.country),
              p.parentCountry_id = coalesce(p.parentCountry_id, pl.parentCountry_id),
              p.parentCountry = coalesce(p.parentCountry, pl.parentCountry),
              p.continent_id = coalesce(p.continent_id, pl.continent_id),
              p.continent = coalesce(p.continent, pl.continent)
        ';
        $updates[] = '
          update volunteerPeriod vp
            left join period p
              on vp.period_id = p.id
            set
              vp.length = TIMEDIFF(p.endTime,p.startTime)
        ';
        $updates[] = 'update volunteer set alloc = null';
        $updates[] = '
          update volunteer v
            left join (
              select
                volunteer_id,
                sec_to_time(sum(time_to_sec(length))) as alloc
              from volunteerPeriod
              where task_id is not null
              group by volunteer_id
            ) as vp
              on vp.volunteer_id = v.id
            set
              v.alloc = vp.alloc
        ';
      break;
      case 'country':
        $updates[] = 'update country set id_country = id where id_country is null';
      break;
    }
    if ($updates and is_array($updates)) {
      foreach ($updates as $update) {
        $dbh->query($update);
      }
    }
  }
  
  function deNormHelper($class, $parent, $selfParent = false) {
    global $classes;
    unset($set);
    $join = 'update '.$class." t1 \n";
    $join .= ' left join '.$parent." t2 \n";
    $join .= ' on t1.'.(($selfParent) ? 'parent'.ucfirst($parent) : $parent)."_id = t2.id \n";
    if ($classes->{$parent}->selfParent && !$selfParent) {
      $set[] = ' t1.parent'.ucfirst($parent).'_id = coalesce(t1.parent'.ucfirst($parent).'_id, t2.parent'.ucfirst($parent)."_id)\n";
    }
    foreach ($classes->{$parent}->parents as $parents) {
      $set[] = ' t1.'.$parents.'_id = coalesce(t1.'.$parents.'_id, t2.'.$parents."_id)\n";
      if ($classes->{$parents}->selfParent) {
        $set[] = ' t1.parent'.ucfirst($parents).'_id = coalesce(t1.parent'.ucfirst($parents).'_id, t2.parent'.ucfirst($parents)."_id)\n";
      }
    }
    $sets = implode($set, ',');
    $where = " where t2.id is not null\n";
    return $join.' set '.$sets.$where;
  }
  
  function getManufacturers($dbh, $where = false, $order = 'order by mn.name') {
    $query = '
      select
        mn.id as id,
        mn.name as name,
        "manufacturer" as class
      from manufacturer mn
      left join game g
        on g.manufacturer_id = mn.id
      left join machine ma
        on ma.game_id = g.id
      left join tournamentDivision d
        on d.id = ma.tournamentDivision_id
      left join tournamentEdition e
        on e.id = d.tournamentEdition_id
    '; 
    $where = preg_replace('/ id /', ' mn.id ', $where);
    $where = preg_replace('/ game_id /', ' g.id ', $where);
    $where = preg_replace('/ ipdb_id /', ' g.game_ipdb_id ', $where);
    $where = preg_replace('/ tournamentDivision_id /', ' d.id ', $where);
    $where = preg_replace('/ tournamentEdition_id /', ' e.id ', $where);
    $where = preg_replace('/ type = main /', ' d.id = 1 ', $where);
    $where = preg_replace('/ type = classics /', ' d.id = 2 ', $where);
    $sth = $dbh->query($query.' '.$where.' group by mn.id '.$order);
    while ($obj = $sth->fetchObject('manufacturer')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getSetSelect() {
    return getMatchSelect(true);
  }
  
  function getMatchSelect($set = false) {
    $return = '
      '.(($set) ? 's' : 'm').'.id as id,
      '.(($set) ? 'm.id as match_id,
      s.machine_id as machine_id,
      s.game_id as game_id,
      s.game as game,
      s.gameAcronym as gameShortName,' : '').'
      p1.id as player1_id,
      concat(ifnull(p1.firstName,""), " ", ifnull(p1.lastName,"")) as player1,
      p1.initials as player1_initials,
      p2.id as player2_id,
      concat(ifnull(p2.firstName,""), " ", ifnull(p2.lastName,"")) as player2,
      p2.initials as player2_initials,
      if(ifnull(p1.place,0) = 1, p1.id, if(ifnull(p2.place,0) = 1, p2.id)) as winner_id,
      if(ifnull(p1.place,0) = 1, concat(ifnull(p1.firstName,""), " ", ifnull(p1.lastName,"")), if(ifnull(p2.place,0) = 1, concat(ifnull(p2.firstName,""), " ", ifnull(p2.lastName,"")))) as winner,
      if(ifnull(p1.place,0) = 1, p1.initials, if(ifnull(p2.place,0) = 1, p2.initials)) as winner_initials
    FROM match'.(($set) ? 'Set s' : ' m').'
      '.(($set) ? 'LEFT JOIN match m
        ON s.match_id = m.id' : '').'
      LEFT JOIN matchPlayer p1 
        ON p1.match_id = m.id
      LEFT JOIN matchPlayer p2
        ON p2.match_id = m.id
    ORDER BY m.id'.(($set) ? ', s.id' : '');
  }

  function getGameSelect($machine = false, $groupBy = true) {
    return '
      select
        '.(($machine) ? 'ma' : 'g').'.id as id,
        g.name as name,
        "game" as class,
        g.id as game_id,
        ma.id as machine_id,
        ma.owner_id as owner_id,
        ma.owner as owner,
        ma.ownerShortName as ownerShortName,
        ma.ownerAccount as ownerAccount,
        ma.paid as ownerPaid,
        mn.id as manufacturer_id,
        mn.name as manufacturer,
        g.acronym as acronym,
        g.acronym as shortName,
        ma.recreational as recreational,
        ma.side as side,
        ma.gameType as gameType,
        ma.balls as balls,
        ma.extraBalls as extraBalls,
        ma.onePlayerAllowed as onePlayerAllowed,
        if(g.game_ipdb_id is not null, 1, 0) as isIpdb,
        g.game_ipdb_id as ipdb_id,
        g.game_year_released as year,
        g.game_link_rulesheet as rules,
        if(ma.tournamentDivision_id = 1, "main", if(ma.tournamentDivision_id = 2, "classics", if(ma.tournamentDivision_id = 3, "team", if(ma.tournamentDivision_id = 12, "natTeam", if(ma.tournamentDivision_id = 13, "side", if(ma.tournamentDivision_id = 14, "recreational", null)))))) as type,
        '.(($groupBy) ? 'if(sum(ma.tournamentDivision_id = 1) > 0, 1, 0) as main,
        if(sum(ma.tournamentDivision_id = 2) > 0, 1, 0) as classics,
        if(sum(ma.tournamentDivision_id = 3) > 0, 1, 0) as team,
        if(sum(ma.tournamentDivision_id = 12) > 0, 1, 0) as natTeam,
        if(sum(ma.tournamentDivision_id = 13) > 0, 1, 0) as side,
        if(sum(ma.tournamentDivision_id = 14) > 0, 1, 0) as recreational,
        ' : 'if(ma.tournamentDivision_id = 1, 1, 0) as main,
        if(ma.tournamentDivision_id = 2, 1, 0) as classics,
        if(ma.tournamentDivision_id = 3, 1, 0) as team,
        if(ma.tournamentDivision_id = 12, 1, 0) as natTeam,
        if(ma.tournamentDivision_id = 13, 1, 0) as side,
        if(ma.tournamentDivision_id = 14, 1, 0) as recreational,').'
        ma.tournamentDivision_id as tournamentDivision_id,
        ma.tournamentEdition_id as tournamentEdition_id,
        ma.comment as comment
      '.(($machine) ? 'from machine ma
        left join game g
          on g.id = ma.game_id
      ' : 'from game g
        left join machine ma
          on g.id = ma.game_id').'
        left join manufacturer mn
          on g.manufacturer_id = mn.id
    ';
  }

  function getGames($dbh, $where = false, $order = 'order by g.name', $tournament = 1, $groupBy = 'group by g.id') {
    $query = getGameSelect($groupBy);
    $where = preg_replace('/ id /', ' g.id ', $where);
    $where = preg_replace('/ manufacturer_id /', ' ma.manufacturer_id ', $where);
    $where = preg_replace('/ game_id /', ' g.id ', $where);
    $where = preg_replace('/ ipdb_id /', ' g.game_ipdb_id ', $where);
    $where = preg_replace('/ tournamentDivision_id /', ' ma.tournamentDivision_id ', $where);
    $where = preg_replace('/ tournamentEdition_id /', ' ma.tournamentEdition_id ', $where);
    $where = preg_replace('/ type = main /', ' ma.tournamentDivision_id = 1 ', $where);
    $where = preg_replace('/ type = classics /', ' ma.tournamentDivision_id = 2 ', $where);
    if ($tournament) {
      $where = (($where) ? $where.' and' : ' where').' ma.tournamentEdition_id = '.$tournament;
    }    
    echo($query.' '.$where.' '.$groupBy.' '.$order);
    $sth = $dbh->query($query.' '.$where.' '.$groupBy.' '.$order);
    while ($obj = $sth->fetchObject('game')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getGamesByDivision($dbh, $division) {
    return getGames($dbh, 'where tournamentDivision_id = '.$division, 'order by g.name', 1, '');
  }
  
  function getMachines($dbh, $where, $order = 'order by g.name') {
    $query = getGameSelect(true, false);
    $where = preg_replace('/ id /', ' ma.id ', $where);
    $where = preg_replace('/ game_id /', ' ma.game_id ', $where);
    $where = preg_replace('/ manufacturer_id /', ' ma.manufacturer_id ', $where);
    $where = preg_replace('/ tournamentDivision_id /', ' ma.tournamentDivision_id ', $where);
    $where = preg_replace('/ tournamentEdition_id /', ' ma.tournamentEdition_id ', $where);
    $where = preg_replace('/ type = main /', ' ma.tournamentDivision_id = 1 ', $where);
    $where = preg_replace('/ type = classics /', ' ma.tournamentDivision_id = 2 ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('game')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getOwners($dbh, $where = null, $order = 'order by name') {
    $query = 'select * from owner';
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject()) {
      $objs[] = $obj;
    }
    return $objs;
  }

  function getOwnerById($dbh, $id) {
    if ($id) {
      $owner = getOwners($dbh, 'where id = '.$id);
      if ($owner[0] && $owner[0]->id == $id) {
        return $owner[0];
      }
    }
    return false;
  }

  function getGenders($dbh, $where, $order = 'order by name') {
    $query = '
      select
        ge.id as id,
        ge.id as continent_id,
        "gender" as class,
        "gender" as type,
        ge.name as name,
        ge.comment as comment
      from gender ge
    ';
    $where = preg_replace('/ id /', ' ge.id ', $where);
    $where = preg_replace('/ gender_id /', ' ge.id ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('gender')) {
      $objs[] = $obj;
    }
    return $objs;
  }

  function getContinents($dbh, $where, $order = 'order by name') {
    $query = '
      select
        cn.id as id,
        cn.id as continent_id,
        "continent" as class,
        "continent" as type,
        cn.name as name,
        cn.latitude as latitude,
        cn.longitude as longitude,
        cn.comment as comment
      from continent cn
    '; 
    $where = preg_replace('/ id /', ' cn.id ', $where);
    $where = preg_replace('/ continent_id /', ' cn.id ', $where);
//    echo($query.' '.$where.' '.$order);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('continent')) {
      $objs[] = $obj;
    }
    return $objs;
  }  
  
  function getCountries($dbh, $where, $order = 'order by name') {
    $query = '
      select
        co.id as id,
        "country" as class,
        "country" as type,
        co.name as name,
        co.parentCountry_id as parentCountry_id,
        co.parentCountry as parentCountry,
        co.altName as altName,
        co.latitude as latitude,
        co.longitude as longitude,
        co.continent_id as continent_id,
        co.continent as continent,
        co.comment as comment
      from country co
    '; 
    $where = preg_replace('/ id /', ' co.id ', $where);
    $where = preg_replace('/ country_id /', ' co.parentCountry_id ', $where);
    $where = preg_replace('/ parentCountry_id /', ' co.parentCountry_id ', $where);
    $where = preg_replace('/ continent_id /', ' co.continent_id ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('country')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function getRegions($dbh, $where, $order = 'order by name') {
    $query = '
      select
        r.id as id,
        "region" as class,
        "region" as type,
        r.name as name,
        r.parentRegion_id as parentRegion_id,
        r.parentRegion as parentRegion,
        r.altName as altName,
        r.latitude as latitude,
        r.longitude as longitude,
        r.country_id as country_id,
        r.country as country,
        r.parentCountry_id as parentCountry_id,
        r.parentCountry as parentCountry,
        r.continent_id as continent_id,
        r.continent as continent,
        r.comment as comment
      from region r
    '; 
    $where = preg_replace('/ id /', ' r.id ', $where);
    $where = preg_replace('/ region_id /', ' r.parentRegion_id ', $where);
    $where = preg_replace('/ parentRegion_id /', ' r.parentRegion_id ', $where);
    $where = preg_replace('/ country_id /', ' coalesce(r.country_id, r.parentCountry_id) ', $where);
    $where = preg_replace('/ parentCountry_id /', ' r.parentCountry_id ', $where);
    $where = preg_replace('/ continent_id /', ' r.continent_id ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('region')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  
  function getCities($dbh, $where, $order = 'order by name') {
    $query = '
      select
        c.id as id,
        "city" as class,
        "city" as type,
        c.name as name,
        c.altName as altName,
        c.latitude as latitude,
        c.longitude as longitude,
        coalesce(c.region_id, c.parentRegion_id) as region_id,
        coalesce(c.region, c.parentRegion) as region,
        c.parentRegion_id as parentRegion_id,
        c.parentRegion as parentRegion,
        c.country_id as country_id,
        c.country as country,
        c.parentCountry_id as parentCountry_id,
        c.parentCountry as parentCountry,
        c.continent_id as continent_id,
        c.continent as continent,
        c.comment as comment
      from city c
    ';
    $where = preg_replace('/ id /', ' c.id ', $where);
    $where = preg_replace('/ city_id /', ' c.id ', $where);
    $where = preg_replace('/ region_id /', ' coalesce(c.region_id, c.parentRegion_id) ', $where);
    $where = preg_replace('/ parentRegion_id /', ' c.parentRegion_id ', $where);
    $where = preg_replace('/ country_id /', ' coalesce(c.country_id, c.parentCountry_id) ', $where);
    $where = preg_replace('/ parentCountry_id /', ' c.parentCountry_id ', $where);
    $where = preg_replace('/ continent_id /', ' c.continent_id ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('city')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  
  function getPlayerJoin() {
    return '
      left join player m
        on m.person_id = p.id and m.tournamentDivision_id = 1
      left join player cl
        on cl.person_id = p.id and cl.tournamentDivision_id = 2
      left join volunteer v 
        on v.person_id = p.id and v.tournamentEdition_id = 1 
      left join city pC
        on p.city_id = pC.id
      left join region pR
        on p.region_id = pR.id
      left join region pRPr
        on pR.parentRegion_id = pRPr.id
      left join region pCR
        on pC.region_id = pCR.id
      left join region pCRPr
        on pCR.parentRegion_id = pCRPr.id
      left join country pCo
        on p.country_id = pCo.id
      left join country pCoPco
        on pCo.parentCountry_id = pCoPco.id
      left join country pCCo
        on pC.country_id = pCCo.id
      left join country pCCoPco
        on pCCo.parentCountry_id = pCCoPco.id
      left join country pRCo
        on pR.country_id = pRCo.id
      left join country pRCoPco
        on pRCo.parentCountry_id = pRCoPco.id
      left join country pRPrCo
        on pRPr.country_id = pRPrCo.id
      left join country pRPrCoPco
        on pRPrCo.parentCountry_id = pRPrCoPco.id
      left join country pCRCo
        on pCR.country_id = pCRCo.id
      left join country pCRCoPco
        on pCRCo.parentCountry_id = pCRCoPco.id
      left join country pCRPrCo
        on pCRPr.country_id = pCRPrCo.id
      left join country pCRPrCoPco
        on pCRPrCo.parentCountry_id = pCRPrCoPco.id
      left join continent pCn
        on p.continent_id = pCn.id
      left join continent pCoCn
        on pCo.continent_id = pCoCn.id
      left join continent pCoPcoCn
        on pCoPco.continent_id = pCoPcoCn.id
      left join continent pCCn
        on pC.continent_id = pCCn.id
      left join continent pCCoCn
        on pCCo.continent_id = pCCoCn.id
      left join continent pCCoPcoCn
        on pCCoPco.continent_id = pCCoPcoCn.id
      left join continent pRCn
        on pR.continent_id = pRCn.id
      left join continent pRCoCn
        on pRCo.continent_id = pRCoCn.id
      left join continent pRCoPcoCn
        on pRCoPco.continent_id = pRCoPcoCn.id
      left join continent pRPrCn
        on pRPr.continent_id = pRPrCn.id
      left join continent pRPrCoCn
        on pRPrCo.continent_id = pRPrCoCn.id
      left join continent pRPrCoPcoCn
        on pRPrCoPco.continent_id = pRPrCoPcoCn.id
      left join continent pCRCn
        on pCR.continent_id = pCRCn.id
      left join continent pCRCoCn
        on pCRCo.continent_id = pCRCoCn.id
      left join continent pCRCoPcoCn
        on pCRCoPco.continent_id = pCRCoPcoCn.id
      left join continent pCRPrCn
        on pCRPr.continent_id = pCRPrCn.id
      left join continent pCRPrCoCn
        on pCRPrCo.continent_id = pCRPrCoCn.id
      left join continent pCRPrCoPcoCn
        on pCRPrCoPco.continent_id = pCRPrCoPcoCn.id
      left join gender g
        on p.gender_id = g.id
      left join tournamentDivision mT
        on m.tournamentDivision_id = mT.id
      left join tournamentDivision clT
        on cl.tournamentDivision_id = clT.id
      left join tournamentEdition e
        on (mT.tournamentEdition_id = e.id or clT.tournamentEdition_id = e.id or v.tournamentEdition_id = e.id) and e.id = 1 
    ';
  }
  /* 
  function getPlayers($dbh, $where = null, $order = 'order by p.firstName, p.lastName') {
    $query = '
      select 
        p.id as id,
        "player" as class,
        "player" as type,
        p.firstName as firstName,
        p.lastName as lastName,
        trim(concat(ifnull(p.firstName,"")," ",ifnull(p.lastName,""))) as name,
        p.initials as initials,
        g.id as gender_id,
        g.name as gender,
        p.streetAddress as streetAddress,
        p.zipCode as zipCode,
        pC.id as city_id,
        pC.name as city,
        coalesce(pR.id, pRPr.id, pCR.id, pCRPr.id) as region_id,
        coalesce(pR.name, pRPr.name, pCR.name, pCRPr.name) as region,
        coalesce(pRPr.id, pCRPr.id) as parentRegion_id,
        coalesce(pRPr.name, pCRPr.name) as parentRegion,
        coalesce(pCo.id, pCoPco.id, pRCo.id, pRCoPco.id, pRPrCo.id, pRPrCoPco.id, pCCo.id, pCCoPco.id, pCRCo.id, pCRCoPco.id, pCRPrCo.id, pCRPrCoPco.id) as country_id,
        coalesce(pCo.name, pCoPco.name, pRCo.name, pRCoPco.name, pRPrCo.name, pRPrCoPco.name, pCCo.name, pCCoPco.name, pCRCo.name, pCRCoPco.name, pCRPrCo.name, pCRPrCoPco.name) as country,
        coalesce(pCoPco.id, pRCoPco.id, pRPrCoPco.id, pCCoPco.id, pCRCoPco.id, pCRPrCoPco.id) as parentCountry_id,
        coalesce(pCoPco.name, pRCoPco.name, pRPrCoPco.name, pCCoPco.name, pCRCoPco.name, pCRPrCoPco.name) as parentCountry,
        coalesce(pCn.id, pCoCn.id, pCoPcoCn.id, pRCn.id, pRCoCn.id, pRCoPcoCn.id, pRPrCn.id, pRPrCoCn.id, pRPrCoPcoCn.id, pCCn.id, pCCoCn.id, pCCoPcoCn.id, pCRCn.id, pCRCoCn.id, pCRCoPcoCn.id, pCRPrCn.id, pCRPrCoCn.id, pCRPrCoPcoCn.id) as continent_id,
        coalesce(pCn.name, pCoCn.name, pCoPcoCn.name, pRCn.name, pRCoCn.name, pRCoPcoCn.name, pRPrCn.name, pRPrCoCn.name, pRPrCoPcoCn.name, pCCn.name, pCCoCn.name, pCCoPcoCn.name, pCRCn.name, pCRCoCn.name, pCRCoPcoCn.name, pCRPrCn.name, pCRPrCoCn.name, pCRPrCoPcoCn.name) as continent,
        p.telephoneNumber as telephoneNumber,
        p.mobileNumber as mobileNumber,
        p.mailAddress as mailAddress,
        p.birthDate as birthDate,
        p.ifpa_id as ifpa_id,
        p.ifpaRank as ifpaRank,
        p.comment as comment,
        if(p.ifpa_id is not null,1,0) as isIfpa,
        null as password,
        if(p.id is not null,1,0) as isPerson,
        if(m.id is not null or cl.id is not null,1,0) as isPlayer,
        if(m.id is not null,1,0) as main,
        if(cl.id is not null,1,0) as classics,
        if(v.id is not null,1,0) as volunteer,
        e.id as tournamentEdition_id,
        p.username as username,
        if(p.password is null,1,0) as passwordRequired
      from person p 
    '.getPlayerJoin();
    $where = preg_replace('/ tournamentEdition_id /', ' mT.tournamentEdition_id ', $where);
    $where = preg_replace('/ id /', ' p.id ', $where);
    $where = preg_replace('/ player_id /', ' p.id ', $where);
    $where = preg_replace('/ city_id /', ' pC.id ', $where);
    $where = preg_replace('/ region_id /', ' coalesce(pR.id, pRPr.id, pCR.id, pCRPr.id) ', $where);
    $where = preg_replace('/ parentRegion_id /', ' coalesce(pRPr.id, pCRPr.id) ', $where);
    $where = preg_replace('/ country_id /', ' coalesce(pCo.id, pCoPco.id, pRCo.id, pRCoPco.id, pRPrCo.id, pRPrCoPco.id, pCCo.id, pCCoPco.id, pCRCo.id, pCRCoPco.id, pCRPrCo.id, pCRPrCoPco.id) ', $where);
    $where = preg_replace('/ parentCountry_id /', ' coalesce(pCoPco.id, pRCoPco.id, pRPrCoPco.id, pCCoPco.id, pCRCoPco.id, pCRPrCoPco.id) ', $where);
    $where = preg_replace('/ continent_id /', ' coalesce(pCn.id, pCoCn.id, pCoPcoCn.id, pRCn.id, pRCoCn.id, pRCoPcoCn.id, pRPrCn.id, pRPrCoCn.id, pRPrCoPcoCn.id, pCCn.id, pCCoCn.id, pCCoPcoCn.id, pCRCn.id, pCRCoCn.id, pCRCoPcoCn.id, pCRPrCn.id, pCRPrCoCn.id, pCRPrCoPcoCn.id) ', $where);
    //echo($query.' '.$where.' '.$order);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('player')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  */
  
  function getPersonByNonce($dbh, $nonce) {
    $query = '
      select
        p.id as id,
        "player" as class,
        p.firstName as firstName,
        p.lastName as lastName,
        concat(ifnull(p.firstName,""), " ", ifnull(p.lastName,"")) as name,
        p.initials as initials,
        p.streetAddress as streetAddress,
        p.zipCode as zipCode,
        p.gender_id as gender_id,
        p.gender as gender,
        p.city_id as city_id,
        p.city as city,
        p.region_id as region_id,
        p.region as region,
        p.country_id as country_id,
        p.country as country,
        p.continent_id as continent_id,
        p.continent as continent,
        p.parentRegion_id as parentRegion_id,
        p.parentRegion as parentRegion,
        p.parentCountry_id as parentCountry_id,
        p.parentCountry as parentCountry,
        p.telephoneNumber as telephoneNumber,
        p.mobileNumber as mobileNumber,
        p.mailAddress as mailAddress,
        p.birthDate as birthDate,
        p.ifpa_id as ifpa_id,
        p.ifpaRank as ifpaRank,
        p.comment as comment,
        if(p.ifpa_id is not null,1,0) as isIfpa,
        1 as isPerson,
        p.username as username,
        null as password
      from person p
      where p.nonce = :nonce
      order by p.firstName, p.lastName
      limit 1
    ';
    $select[':nonce'] = $nonce;
    $sth = $dbh->prepare($query);
    if ($sth->execute($select)) {
      while ($obj = $sth->fetchObject('player')) {
        return $obj;
      }
    }
    return false;
  }

  function getPersonById($dbh, $id) {
    $query = '
      select 
        p.id as id,
        "player" as class,
        p.firstName as firstName,
        p.lastName as lastName,
        concat(ifnull(p.firstName,""), " ", ifnull(p.lastName,"")) as name,
        p.initials as initials,
        p.streetAddress as streetAddress,
        p.zipCode as zipCode,
        p.gender_id as gender_id,
        p.gender as gender,
        p.city_id as city_id,
        p.city as city,
        p.region_id as region_id,
        p.region as region,
        p.country_id as country_id,
        p.country as country,
        p.continent_id as continent_id,
        p.continent as continent,
        p.parentRegion_id as parentRegion_id,
        p.parentRegion as parentRegion,
        p.parentCountry_id as parentCountry_id,
        p.parentCountry as parentCountry,
        p.telephoneNumber as telephoneNumber,
        p.mobileNumber as mobileNumber,
        p.mailAddress as mailAddress,
        p.birthDate as birthDate,
        p.ifpa_id as ifpa_id,
        p.ifpaRank as ifpaRank,
        p.comment as comment,
        if(p.ifpa_id is not null,1,0) as isIfpa,
        1 as isPerson,
        p.username as username,
        null as password
      from person p
      where p.id = :id
      order by p.firstName, p.lastName
    ';
    $select[':id'] = $id;
    $sth = $dbh->prepare($query);
    if ($sth->execute($select)) {
      while ($obj = $sth->fetchObject('player')) {
        return $obj;
      }
    }
    return false;
  }
  
  function getPlayerSelect($extra = null){
    $query = '
      select 
        p.id as id,
        "player" as class,
        "player" as type,
        coalesce(m.firstName, p.firstName) as firstName,
        coalesce(m.lastName, p.lastName) as lastName,
        coalesce(m.initials, p.initials) as initials,
        coalesce(m.streetAddress, p.streetAddress) as streetAddress,
        coalesce(m.zipCode, p.zipCode) as zipCode,
        coalesce(m.gender_id, p.gender_id) as gender_id,
        coalesce(m.gender, p.gender) as gender,
        coalesce(m.city_id, p.city_id) as city_id,
        coalesce(m.city, p.city) as city,
        coalesce(m.region_id, p.region_id) as region_id,
        coalesce(m.region, p.region) as region,
        coalesce(m.country_id, p.country_id) as country_id,
        coalesce(m.country, p.country) as country,
        coalesce(m.continent_id, p.continent_id) as continent_id,
        coalesce(m.continent, p.continent) as continent,
        coalesce(m.parentRegion_id, p.parentRegion_id) as parentRegion_id,
        coalesce(m.parentRegion, p.parentRegion) as parentRegion,
        coalesce(m.parentCountry_id, p.parentCountry_id) as parentCountry_id,
        coalesce(m.parentCountry, p.parentCountry) as parentCountry,
        coalesce(m.telephoneNumber, p.telephoneNumber) as telephoneNumber,
        coalesce(m.mobileNumber, p.mobileNumber) as mobileNumber,
        coalesce(m.mailAddress, p.mailAddress) as mailAddress,
        coalesce(m.birthDate, p.birthDate) as birthDate,
        coalesce(m.dateRegistered, p.dateRegistered) as dateRegistered,
        if(m.birthDate is not null,if(m.birthDate > "1995-11-07",1,0),if(p.birthDate is not null,if(p.birthDate > "1995-11-07",1,0),0)) as u18,
        if(m.birthDate is not null,if(m.birthDate > "2006-11-07",1,0),if(p.birthDate is not null,if(p.birthDate > "2006-11-07",1,0),0)) as u7,
        m.qualPlace as qualPlace,
        m.place as place,
        ifnull(m.wpprPlace, m.place) as wpprPlace,
        cl.qualPlace as classicsQualPlace,
        cl.place as classicsPlace,
        ifnull(cl.wpprPlace, cl.place) as classicsWpprPlace,
        if(ifnull(m.here, 0) = 1 or ifnull(cl.here, 0) = 1, 1, 0) as here,
        m.here as hereMain,
        cl.here as hereClassics,
        if(ifnull(m.hereFinal, 0) = 1 or ifnull(cl.hereFinal, 0) = 1, 1, 0) as hereFinal,
        m.hereFinal as hereFinalMain,
        cl.hereFinal as hereFinalClassics,
        v.here as hereVol,
        m.ifpa_id as ifpa_id,
        m.ifpaRank as ifpaRank,
        m.adminLevel as adminLevel,
        m.comment as comment,
        if(m.ifpa_id is not null,1,0) as isIfpa,
        null as password,
        if(m.person_id is not null,1,0) as isPerson,
        if(m.id is not null or cl.id is not null,1,0) as isPlayer,
        if(m.id is not null,1,0) as main,
        if(cl.id is not null,1,0) as classics,
        if(m.id is not null,m.id,null) as mainPlayerId,
        if(cl.id is not null,cl.id,null) as classicsPlayerId,
        m.qualGroup_id as qualGroup_id,
        m.qualGroup_id as mainQualGroup_id,
        cl.qualGroup_id as classicsQualGroup_id,
        m.qualChangeReq as qualChangeReq,
        cl.qualChangeReq as classicsQualChangeReq,
        if(v.id is not null,1,0) as volunteer,
        v.id as volunteer_id,
        ifnull(v.hours, 0) as hours,
        ifnull(v.alloc, 0) as alloc,
        timediff(time(concat(ifnull(v.hours, "00"), ":00:00")), ifnull(v.alloc, time("00:00:00"))) as hoursDiff,
        if(m.paid is not null, m.paid, 0) as paid,
        m.payDate as payDate,
        m.tournamentEdition_id as tournamentEdition_id,
        p.username as username,
        if(p.password is null,1,0) as passwordRequired
        '.(($extra) ? ', '.$extra : '').'
      from person p
      left join player m
        on m.person_id = p.id and m.tournamentDivision_id = 1
      left join player cl
        on cl.person_id = p.id and cl.tournamentDivision_id = 2
      left join volunteer v
        on v.person_id = p.id and v.tournamentEdition_id = 1
    ';
    return $query;
  }
  
  function getPlayers($dbh, $where = null, $order = 'order by p.firstName, p.lastName') {
    $query = getPlayerSelect();
    $where = preg_replace('/ tournamentEdition_id /', ' m.tournamentEdition_id ', $where);
    $where = preg_replace('/ id /', ' p.id ', $where);
    $where = preg_replace('/ player_id /', ' p.id ', $where);
    $where = preg_replace('/ city_id /', ' m.city_id ', $where);
    $where = preg_replace('/ region_id /', ' m.region_id ', $where);
    $where = preg_replace('/ parentRegion_id /', ' m.parentRegion_id ', $where);
    $where = preg_replace('/ country_id /', ' m.country_id ', $where);
    $where = preg_replace('/ parentCountry_id /', ' m.parentCountry_id ', $where);
    $where = preg_replace('/ continent_id /', ' m.continent_id ', $where);
    //echo($query.' '.$where.' '.$order);
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject('player')) {
      $objs[] = $obj;
    }
    return $objs;
  }
    
  function getPlayerByIfpaId($dbh, $ifpaId, $type = null) {
    if(preg_match('/@/',$ifpaId)) {
      $where = ' and p.mailAddress = "'.$ifpaId.'"';
    } else if (preg_match('/^[0-9]{1,5}$/', $ifpaId)) {
      $where = ' and p.ifpa_id = '.$ifpaId;
    } else if (preg_match('/^[0-9 \-\+\(\)]{6,}$/',$ifpaId)) {
      $where = ' and replace(replace(replace(replace(replace(p.telephoneNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$ifpaId).'%" or replace(replace(replace(replace(replace(p.mobileNumber," ",""),")",""),")",""),"-",""),"+","") like "%'.preg_replace('/[^0-9]/','',$ifpaId).'%"';
    } else if (preg_match('/^[a-zA-Z0-9 \-]{3}$/',$ifpaId)) {
      $where = ' and p.initials like "'.preg_replace('/ $/','',$ifpaId).'"';
    } else {
      $where = ' and concat(ifnull(p.firstName,""), " ", ifnull(p.lastName,"")) like "%'.$ifpaId.'%"';
    }
        // Hard coded shit! Remove when chance is given!
    $where .= (preg_match('/flippersm/', __baseHref__)) ? ' and p.country = "Sweden"' : '';
    $query = '
      select 
        p.id as id,
        "player" as class,
        "player" as type,
        p.firstName as firstName,
        p.lastName as lastName,
        p.initials as initials,
        p.streetAddress as streetAddress,
        p.zipCode as zipCode,
        p.gender_id as gender_id,
        p.gender as gender,
        p.city_id as city_id,
        p.city as city,
        p.region_id as region_id,
        p.region as region,
        p.country_id as country_id,
        p.country as country,
        p.continent_id as continent_id,
        p.continent as continent,
        p.parentRegion_id as parentRegion_id,
        p.parentRegion as parentRegion,
        p.parentCountry_id as parentCountry_id,
        p.parentCountry as parentCountry,
        p.telephoneNumber as telephoneNumber,
        p.mobileNumber as mobileNumber,
        p.mailAddress as mailAddress,
        p.birthDate as birthDate,
        p.ifpa_id as ifpa_id,
        p.ifpaRank as ifpaRank,
        p.comment as comment,
        1 as passwordRequired,
        if(p.ifpa_id is not null,1,0) as isIfpa,
        if(p.id is not null,1,0) as isPerson
      from person p
      left join player m
        on m.person_id = p.id and m.tournamentDivision_id = 1
      left join player cl
        on cl.person_id = p.id and cl.tournamentDivision_id = 2
      where m.id is null and cl.id is null
    ';
    $sth = $dbh->query($query.' '.$where.' order by p.firstName, p.lastName');
    while ($obj = $sth->fetchObject('player')) {
      $objs[] = $obj;
    }
    return $objs;
  } 

  function scoreComp($score1, $score2) {
    return ($score1->score == $score2->score) ? 0 : (($score1->score > $score2->score) ? -1 : 1);
  }
  
  function entryComp($entry1, $entry2) {
    return ($entry1->points == $entry2->points) ? 0 : (($entry1->points > $entry2->points) ? -1 : 1);
  }

  function calcScorePlaces($dbh, $division = 1) {
    $games = getGamesByDivision($dbh, $division);
    if ($games) {
      echo $game->name.'...';
      foreach ($games as $game) {
        $game->setPlaces($dbh, $division);
      }
    }
  }
  
  function calcEntryPlaces($dbh, $division, $calcPoints = true) {
    clearEntryPlaces($dbh, $division);
    $entries = getEntriesByDivision($dbh, $division);
    if ($entries) {
      foreach ($entries as $entry) {
        if ($calcPoints) {
          $entry->points = $entry->calcPoints($dbh);
        }
        if ($entry->points) {
          $pointsEntries[] = $entry;
        }
      }
      if ($pointsEntries) {
        usort($pointsEntries, 'entryComp');
        $place = 0;
        foreach ($pointsEntries as $pointsEntry) {
          $place++;
          $pointsEntry->setPlace($dbh, $place);
        }
      }
      return true;
    } else {
      return false;
    }
  }

  function clearScorePlaces($dbh, $division = 1) {
    $query = 'update qualScore set place = null where tournamentDivision_id = '.$division;
    $sth = $dbh->prepare($query);
    return $sth->execute($update);
  }

  function clearEntryPlaces($dbh, $division = 1) {
    $query = 'update qualEntry set place = null where tournamentDivision_id = '.$division;
    $sth = $dbh->prepare($query);
    return $sth->execute($update);
  }

  function updateScore($dbh, $idScore, $iScore)
  {
    $query = 'update qualScore s set s.score = ' . $iScore;
    $query .= ' where s.id = ' . $idScore;
    //print 'Query: ' . $query;
    $sth = $dbh->prepare($query);
    $sth->execute($update);
  }

  function getEntriesByDivision($dbh, $division = 1) {
    if ($division) {
      $where = 'where qe.tournamentDivision_id = '.$division;
      return getEntries($dbh, $where);
    } else {
      return false;
    }
  }

  function getEntries($dbh, $where = null, $order = 'order by qe.points desc, qe.place asc', $groupBy = 'group by qe.id') {
    $query = getEntrySelect().'
      left join qualScore qs
        on qe.id = qs.qualEntry_id
    ';
    $where = preg_replace('/ id /', ' qe.id ', $where);
    $where = preg_replace('/ name /', ' qe.name ', $where);
    $where = preg_replace('/ person_id /', ' qe.person_id ', $where);
    $where = preg_replace('/ player_id /', ' qe.player_id ', $where);
    $where = preg_replace('/ tournamentDivision_id /', ' qe.tournamentDivision_id ', $where);
    $where = preg_replace('/ tournamentEdition_id /', ' qe.tournamentEdition_id ', $where);
    $where = preg_replace('/ firstName /', ' qe.firstName ', $where);
    $where = preg_replace('/ lastName /', ' qe.lastName ', $where);
    $where = preg_replace('/ country_id /', ' qe.countryId ', $where);
    $where = preg_replace('/ country /', ' qe.country ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$groupBy.' '.$order);
    while ($obj = $sth->fetchObject('entry')) {
      $objs[] = $obj;
    }
    return $objs;
  }

  function getEntry($dbh, $idPlayer, $idDivision) {
    $where = 'where qe.'.(($idDivision == 3) ? 'player' : 'person').'_id = '.$idPlayer.' and qe.tournamentDivision_id = '.$idDivision;
    if ($obj = getEntries($dbh, $where)[0]) {
      return $obj;
    } else {
      return false;
    }
  }

  function getScores($dbh, $where = null, $order = 'order by max(qs.points) desc, min(qs.place) asc', $gruopBy = 'group by qs.machine_id') {
    $query = getScoreSelect();
    $where = preg_replace('/ id /', ' qs.id ', $where);
    $where = preg_replace('/ name /', ' qs.name ', $where);
    $where = preg_replace('/ person_id /', ' qs.person_id ', $where);
    $where = preg_replace('/ player_id /', ' qs.player_id ', $where);
    $where = preg_replace('/ tournamentDivision_id /', ' qs.tournamentDivision_id ', $where);
    $where = preg_replace('/ tournamentEdition_id /', ' qs.tournamentEdition_id ', $where);
    $where = preg_replace('/ firstName /', ' qs.firstName ', $where);
    $where = preg_replace('/ lastName /', ' qs.lastName ', $where);
    $where = preg_replace('/ country_id /', ' qs.countryId ', $where);
    $where = preg_replace('/ country /', ' qs.country ', $where);
    $sth = $dbh->query($query.' '.$where.' '.$groupBy.' '.$order);
    while ($obj = $sth->fetchObject('score')) {
      $objs[] = $obj;
    }
    return $objs;
  }

  function getPlayerAdminLevel($dbh, $userName)
  {
    $id = getIdFromUser($dbh, $userName);
    $player = ($id) ? getPlayerById($dbh, $id) : false;

    $adminLevel = 0;
    if ($player && ($player->adminLevel != null))
    {
      $adminLevel = $player->adminLevel;
    }
    return $adminLevel;
  }

  function getPlayerList($dbh, $division = null, $tournament = '1') {
    $name = 'concat(o.firstName, " ", o.lastName)';
    if ($division) {
      $join = 'left join player l on l.person_id = o.id';
      $where = 'where l.tournamentDivision_id = '.$division;
    } else if ($tournament) {
      $join = 'left join player l on l.person_id = o.id';
      $where = 'where l.tournamentEdition_id = '.$tournament;
    }
    return getObjectListHelper($dbh, 'person', $where, $name, $join);
  }

  function getQualGroupList($dbh, $division = null, $tournament = '1') {
    $name = 'o.name';
    if ($division) {
      $where = 'where o.tournamentDivision_id = '.$division;
    } else if ($tournament) {
      $join = ' left join tournamentDivision td on o.tournamentDivision_id = td.id';
      $where = 'where td.tournamentEdition_id = '.$tournament;
    }
    return getObjectListHelper($dbh, 'qualGroup', $where, $name, $join);
  }

  function getGameList($dbh, $division = null, $tournament = '1') {
    $name = 'o.name';
    if ($division) {
      $join = ' left join machine ma on ma.game_id = o.id';
      $where = 'where ma.tournamentDivision_id = '.$division;
    } else if ($tournament) {
      $join = ' left join machine ma on ma.game_id = o.id';
      $where = 'where ma.tournamentEdition_id = '.$tournament;
    }
    return getObjectListHelper($dbh, 'game', $where, $name, $join);
  }
  
  function getManufacturerList($dbh, $division = null, $tournament = '1') {
    $name = 'o.name';
    if ($division) {
      $join = ' left join game g on g.manufacturer_id = o.id left join machine ma on ma.game_id = g.id';;
      $where = 'where ma.tournamentDivision_id = '.$division;
    } else if ($tournament) {
      $join = ' left join game g on g.manufacturer_id = o.id left join machine ma on ma.game_id = g.id';
      $where = 'where ma.tournamentEdition_id = '.$tournament;
    }
    return getObjectListHelper($dbh, 'manufacturer', $where, $name, $join);
  }
  
  function getCityList($dbh, $division = false, $tournament = false) {
    if ($division || $tournament) {
      $join = ' left join player pl on pl.city_id = o.id';
      $where = ' where pl.id is not null ';
      $where .= ($division) ? ' and pl.tournamentDivision_id = '.$division : '';
      $where .= ($tournament) ? ' and pl.tournamentEdition_id = '.$tournament : '';
    }
    $name = 'o.name';
    return getObjectListHelper($dbh, 'city', $where, $name, $join);
  }
  
  function getRegionList($dbh, $division = false, $tournament = false) {
    if ($division || $tournament) {
      $join = ' left join player pl on pl.region_id = o.id or pl.parentRegion_id = o.id';
      $where = ' where pl.id is not null ';
      $where .= ($division) ? ' and pl.tournamentDivision_id = '.$division : '';
      $where .= ($tournament) ? ' and pl.tournamentEdition_id = '.$tournament : '';
    }
    $name = 'o.name';
    return getObjectListHelper($dbh, 'region', $where, $name, $join);
  }

  function getCountryList($dbh, $division = false, $tournament = false) {
    if ($division || $tournament) {
      $join = ' left join player pl on pl.country_id = o.id or pl.parentCountry_id = o.id';
      $where = ' where pl.id is not null ';
      $where .= ($division) ? ' and pl.tournamentDivision_id = '.$division : '';
      $where .= ($tournament) ? ' and pl.tournamentEdition_id = '.$tournament : '';
    }
    $name = 'o.name';
    return getObjectListHelper($dbh, 'country', $where, $name, $join);
  }

  function getContinentList($dbh, $division = false, $tournament = false) {
    if ($division || $tournament) {
      $join = ' left join player pl on pl.continent_id = o.id';
      $where = ' where pl.id is not null ';
      $where .= ($division) ? ' and pl.tournamentDivision_id = '.$division : '';
      $where .= ($tournament) ? ' and pl.tournamentEdition_id = '.$tournament : '';
    }
    $name = 'o.name';
    return getObjectListHelper($dbh, 'continent', $where, $name, $join);
  }

  function getGenderList($dbh, $division = false, $tournament = false) {
    if ($division || $tournament) {
      $join = ' left join player pl on pl.gender_id = o.id';
      $where = ' where pl.id is not null ';
      $where .= ($division) ? ' and pl.tournamentDivision_id = '.$division : '';
      $where .= ($tournament) ? ' and pl.tournamentEdition_id = '.$tournament : '';
    }
    $name = 'o.name';
    return getObjectListHelper($dbh, 'gender', $where, $name, $join);
  }

  function getTeamList($dbh, $division = false, $tournament = false, $national = false) {
    if ($division || $tournament) {
      $join = ' left join teamPlayer tp on tp.team_id = o.id left join player pl on tp.player_id = pl.id';
      $where = ' where pl.id is not null ';
      $where .= ($division) ? ' and o.tournamentDivision_id = '.$division : '';
      $where .= ($tournament) ? ' and pl.tournamentEdition_id = '.$tournament : '';
      $and = ' and ';
    } else {
      $and = ' where ';
    }
    $where .= ($national) ? $and.' o.national = 1' : ' and (o.national is null or o.national !=1)';
    $name = 'o.name';
    return getObjectListHelper($dbh, 'team', $where, $name, $join);
  }

  function getObjectListHelper($dbh, $type, $where = null, $name = 'o.name', $join = null) {
    $order = ' order by '.$name;
    $groupBy = ' group by o.id';
    $where .= ($where) ? ' and '.$name.' is not null and '.$name.' != ""' : 'where '.$name.' is not null and '.$name.' != ""';
    $query = 'select o.id as id, '.$name.' as name from '.$type.' o '.$join.' '.$where.' '.$groupBy.' '.$order;
    $sth = $dbh->query($query);
//    echo $query;
    while ($obj = $sth->fetchObject(($type == 'person') ? 'player' : $type)) {
      $objs[] = $obj;
    }
    if (count($objs) > 0) {
      return $objs;
    } else {
      return false;
    }
  }
  
  function getObjs($dbh, $class, $tournament = 1) {
    $objs = getObjectList($dbh, $class, array('tournament' => $tournament));
    foreach($objs as $obj) {
      $output[] = getObjectById($dbh, $class, $obj->id);
    }
    return $output;
  }

  function createSelect($objs, $name = 'select', $selectedId = 0, $onchange = 'infoSelected', $disabled = '', $header = 'Välj...') {
    $content = '<select name="'.$name.'" id="'.$name.'" onchange="'.$onchange.'(this);" previous="'.$selectedId.'" '.$disabled.">\n";
    $content .= '<option value="0">'.$header.'</options>';
    if ($objs && count($objs) > 0) {
      foreach($objs as $obj) {
        $content .= '<option value="'.$obj->id.'"';
        if ($obj->id == $selectedId) {
          $content .= ' selected';
        }
        $content .= '>'.$obj->name.'</option>'."\n";
      }
    }
    return $content.'</select>'."\n";
  }
  
  function getManufacturerById($dbh, $id) {
    if ($id) {
      $where = 'where mn.id = '.$id;
      if ($obj = getManufacturers($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  function getGameById($dbh, $id, $tournemant = 1) {
    if ($id) {
      $where = 'where g.id = '.$id;
      if ($obj = getGames($dbh, $where, ' order by g.name ', $tournament)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getMachineById($dbh, $id) {
    if ($id) {
      $where = 'where ma.id = '.$id;
      if ($obj = getMachines($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getPlayerById($dbh, $id) {
    if ($id) {
      $where = 'where p.id = '.$id;
      if ($obj = getPlayers($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  
  function getPlayerByMainId($dbh, $id) {
    if ($id) {
      $where = 'where m.id = '.$id;
      if ($obj = getPlayers($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getPlayerByClassicsId($dbh, $id) {
    if ($id) {
      $where = 'where cl.id = '.$id;
      if ($obj = getPlayers($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getPlayersByCountry($dbh, $country, $tournament = 1) {
    if ($country->id) {
      return getPlayersByCountryId($dbh, $country->id, $tournament);
    } else {
      return false;
    }
  }

  function getPlayersByCountryId($dbh, $countryId, $tournament = 1) {
    if ($countryId) {
      $where = 'where (p.country_id = '.$countryId.' or p.parentCountry_id = '.$countryId.') and m.tournamentEdition_id = '.$tournament;
      return getPlayers($dbh, $where);
    } else {
      return false;
    }
  }

  function getTeamsByDivision($dbh, $division = 3) {
    return getPlayersByDivision($dbh, $division);
  }

  function getPlayersByDivision($dbh, $division = 1) {
    if ($division == 3) {
      $where = 'where tm.tournamentDivision_id = '.$division;
      return getTeams($dbh, $where);
    } else if ($division) {
      $where = 'where m.tournamentDivision_id = '.$division.' or cl.tournamentDivision_id = '.$division;
      return getPlayers($dbh, $where);
    } else {
      return false;
    }
  }

  function getMachinesByDivision($dbh, $division = 1) {
    if ($division) {
      $where = 'where ma.tournamentDivision_id = '.$division;
      return getMachines($dbh, $where);
    } else {
      return false;
    }
  }

  function getEntryById($dbh, $id) {
    if ($id) {
      $where = 'where qe.id = '.$id;
      if ($obj = getEntries($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  function getScoreById($dbh, $id) {
    if ($id) {
      $where = 'where qs.id = '.$id;
      if ($obj = getScores($dbh, $where, null, null)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  function getEntrySelect($groupBy = true) {
    return '
      select
        qe.id as id,
        qe.name as name,
        qe.person_id as person_id,
        if(qe.tournamentDivision_id < 3, qe.player_id, null) as player_id,
        if(qe.tournamentDivision_id = 3, qe.player_id, null) as team_id,
        qe.tournamentDivision_id as tournamentDivision_id,
        qe.tournamentEdition_id as tournamentEdition_id,
        qe.place as place,
        qe.points as points,
        if(qe.tournamentDivision_id < 3, qe.firstName, null) as firstName,
        if(qe.tournamentDivision_id < 3, qe.lastName, null) as lastName,
        qe.initials as initials,
        if(qe.tournamentDivision_id = 3, qe.firstName, null) as team,
        if(qe.tournamentDivision_id < 3, concat(ifnull(qe.firstName, ""), " ", ifnull(qe.lastName, "")), null) as player,
        qe.country_id as country_id,
        qe.country as country,
        qe.city_id as city_id,
        qe.city as city,
        '.(($groupBy) ? 'max(qs.score) as maxScore,
        max(qs.points) as maxPoints,
        min(qs.place) as bestPlace,' : '').'
        "entry" as class
      from qualEntry qe
    ';
  }

  function getScoreSelect($groupBy = true) {
    return '
      select
        qs.id as id,
        qs.name as name,
        qs.person_id as person_id,
        if(qs.tournamentDivision_id < 3, qs.player_id, null) as player_id,
        if(qs.tournamentDivision_id = 3, qs.player_id, null) as team_id,
        qs.qualEntry_id as qualEntry_id,
        qs.qualEntry_id as entry_id,
        qs.tournamentDivision_id as tournamentDivision_id,
        qs.tournamentEdition_id as tournamentEdition_id,
        '.(($groupBy) ? 'min(qs.place) as place,
        max(qs.points) as points,
        max(qs.score) as score,' : '
        qs.place as place,
        qs.points as points,
        qs.order as `order`,
        qs.round as round,
        qs.score as score,').'
        if(qs.points is not null, 1, 0) as valid,
        if(qs.tournamentDivision_id < 3, qs.firstName, null) as firstName,
        if(qs.tournamentDivision_id < 3, qs.lastName, null) as lastName,
        qs.initials as initials,
        if(qs.tournamentDivision_id = 3, qs.firstName, null) as team,
        if(qs.tournamentDivision_id < 3, concat(ifnull(qs.firstName, ""), " ", ifnull(qs.lastName, "")), null) as player,
        qs.country_id as country_id,
        qs.country as country,
        qs.city_id as city_id,
        qs.city as city,
        qs.machine_id as machine_id,
        qs.game_id as game_id,
        qs.game as game,
        qs.gameAcronym as gameShortName,
        qs.registerPerson_id as registerPerson_id
      from qualScore qs
    ';
  }

  function getCityById($dbh, $id) {
    if ($id) {
      $where = 'where c.id = '.$id;
      if ($obj = getCities($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getRegionById($dbh, $id) {
    if ($id) {
      $where = 'where r.id = '.$id;
      if ($obj = getRegions($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getCountryById($dbh, $id) {
    if ($id) {
      $where = 'where co.id = '.$id;
      if ($obj = getCountries($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  function getContinentById($dbh, $id) {
    if ($id) {
      $where = 'where cn.id = '.$id;
      if ($obj = getContinents($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }  

  function getGenderById($dbh, $id) {
    if ($id) {
      $where = 'where ge.id = '.$id;
      if ($obj = getGenders($dbh, $where)[0]) {
        return $obj;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }  

  function countObjects($dbh, $type = 'player', $where = ' where tournamentDivision_id = 1 ') {
    $query = 'select count(*) from '.$type.' '.$where;
    $sth = $dbh->query($query);
    return $sth->fetchColumn();
  }
  
  function getObjectById($dbh, $type, $id) {
    if ($id) {
      switch ($type) {
        case 'qualGroup':
        case 'qualgroup':
        return getQualGroupById($dbh, $id);
        break;
        case 'game':
        return getGameById($dbh, $id);
        break;
        case 'manufacturer':
        return getManufacturerById($dbh, $id);
        break;
        case 'player':
        return getPlayerById($dbh, $id);
        break;
        case 'city':
        return getCityById($dbh, $id);
        break;
        case 'region':
        return getRegionById($dbh, $id);
        break;
        case 'country':
        return getCountryById($dbh, $id);
        break;
        case 'continent':
        return getContinentById($dbh, $id);
        break;
        case 'gender':
        return getGenderById($dbh, $id);
        break;
        case 'team':
        return getTeamById($dbh, $id);
        break;
        default:
        return false;
        break;
      }
    } else {
      return false;
    }
  }
    
  function getObjectList($dbh, $type, $options) {
    switch ($type) {
      case 'qualGroup':
        return getQualGroupList($dbh, $options['division'], $options['tournament']);
      break;
      case 'game':
        return getGameList($dbh, $options['division'], $options['tournament']);
      break;
      case 'manufacturer':
        return getManufacturerList($dbh, $options['division'], $options['tournament']);
      break;
      case 'player':
        return getPlayerList($dbh, $options['division'], $options['tournament']);
      break;
      case 'city':
        return getCityList($dbh, $options['division'], $options['tournament']);
      break;
      case 'region':
        return getRegionList($dbh, $options['division'], $options['tournament']);
      break;
      case 'country':
        return getCountryList($dbh, $options['division'], $options['tournament']);
      break;
      case 'continent':
        return getContinentList($dbh, $options['division'], $options['tournament']);
      break;
      case 'gender':
        return getGenderList($dbh, $options['division'], $options['tournament']);
      break;
      case 'team':
        return getTeamList($dbh, $options['division'], $options['tournament'], $options['national']);
      break;
      default:
        return false;
      break;
    }
  }

  function getPlayerByEmail($dbh, $email) {
    $where = 'where (p.mailAddress = "'.$email.'" or m.mailAddress = "'.$email.'" or cl.mailAddress = "'.$email.'")';
    if ($obj = getPlayers($dbh, $where)[0]) {
      return $obj;
    } else {
      return false;
    }
  }
  
  function getVolunteers($dbh, $tournament = 1) {
    $query = getPlayerSelect();
    $query .= '
      where v.id is not null and m.tournamentEdition_id = '.$tournament.'
      order by p.firstName, p.lastName';
    $sth = $dbh->query($query);
    while ($obj = $sth->fetchObject('player')) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function addGeo($dbh, $geoType, $name, $parentType = null, $parentId = null) {
    $update[':name'] = $name;
    $query = 'insert into '.$geoType.' set name = :name';
    if ($parentType && $parentId) {
      $update[':parentId'] = $parentId;
      $query .= ', '.$parentType.'_id = :parentId';
    }
    $sth = $dbh->prepare($query);
    $sth->execute($update);
    return $dbh->lastInsertId();
  }
  
  function updateGeo($dbh, $geoType, $id, $parentArray){
    $update[':id'] = $id;
    $update[':parentId'] = $parentArray[1];
    $query = 'update '.$geoType.' set '.$parentArray[0].'_id = :parentId where id = :id';
    $sth = $dbh->prepare($query);
    $sth->execute($update);
  }
  
  function addPlayerGeo($dbh, $player) {
    global $geoTypes;
    $update = false;
    foreach ($geoTypes as $geoType) {
      if (!preg_match('/^[0-9]+$/', $player->{$geoType}) && preg_match('/.+/', $player->{$geoType})){
//      if (!preg_match('/^[0-9]+$/', $player->{$geoType})){
//        $geoId = $player->{$geoType};
//        if ($update) {
//          updateGeo($dbh, $geoType, $geoId, $update);
//          $update = false;
//        }
//      } else if (preg_match('/.+/', $player->{$geoType})){
        $player->{$geoType.'_id'} = addGeo($dbh, $geoType, $player->{$geoType}, $parentType, $parentId);
//        if ($update) {
//          updateGeo($dbh, $geoType, $geoId, $update);
//        }
//        $update = array($geoType, $geoId);
      } else if (preg_match('/^[0-9]+$/', $player->{$geoType})) {
        $player->{$geoType.'_id'} = $player->{$geoType};
      }
      $parentType = $geoType;
      $parentId = $player->{$geoType.'_id'};
    }
    deNorm($dbh, $geoType);
    return $player;
  }
  
  function addPlayerQuery($dbh, $player, $type = 'player', $division = 1, $method = 'insert into') {
    global $classes;
    $query = $method.' '.$type.' set';
    $player->initials = substr($player->initials, 0, 3);
    foreach($classes->player->fields as $value => $meta) {
      if ($player->{$value}) {
        switch ($meta->type) {
          case 'select':
            if ($type != 'volunteer') {
              if (preg_match('/^[0-9]+$/', $player->{$value})) {
                $id = $player->{$value};
              } else if (preg_match('/^[0-9]+$/', $player->{$value.'_id'})) {
                $id = $player->{$value.'_id'};
              }
              $update[':'.$value] = $id;
              $query .= ' '.$value.'_id = :'.$value.',';
            }
          break;
          case 'hidden':
          case 'text':
            if ($meta->insert) {
              if ($value == 'id') {
                $field = 'person_id';
              } else {
                $field = $value;
              }
              $update[':'.$field] = $player->{$value};
              $query .= ' '.$field.' = :'.$field.',';
            }
          break;
        }
      }
    }
    switch ($type) {
      case 'player':
        $query .= ' tournamentDivision_id = :division';
        $update[':division'] = $division;
      break;
      case 'volunteer':
        if ($player->hours) {
          $query .= ' hours = :hours, ';
          $update[':hours'] = $player->hours;
        }
        $query .= ' tournamentEdition_id = :division';
        $update[':division'] = $division;
      break;
    }
    return array(rtrim($query, ','), $update);
  }
  
  function addPlayer($dbh, $player, $ulogin = null) {
    $player = addPlayerGeo($dbh, $player);
    if ($player->id == '0') {
      $player->id = addPerson($dbh, $player);
    } 
    if ($player->username && $player->password) {
      updateUser($dbh, $player, $ulogin);
    }
    foreach(array('main', 'classics') as $division) {
      if ($player->{$division} == 'true') {
        $query = addPlayerQuery($dbh, $player, 'player', (($division == 'main') ? 1 : 2));
        $sth = $dbh->prepare($query[0]);
        $sth->execute($query[1]);
      }
    }
    if ($player->volunteer == 'true') {
      if (!getVolunteerById($dbh, $player->id)) {
        if (checkPlayer($dbh, $player, 'volunteer')) {
          $player->addVolunteer($dbh, 1, 'update');        
        } else {
          $player->addVolunteer($dbh, 1);
        }
      }
    }
    deNorm($dbh, 'player');
  }
  
  function editPlayer($dbh, $player, $ulogin = null) {
    $pId = $player->id;
    $player = addPlayerGeo($dbh, $player);
    $player->initials = substr($player->initials, 0, 3);
    foreach(array('main', 'classics') as $division) {
      if ($player->{$division} == 'true') {
        if (checkPlayer($dbh, $player, $division)) {
          $query = addPlayerQuery($dbh, $player, 'player', (($division == 'main') ? 1 : 2), 'update');
          $query[0] .= ' where person_id = :pId and tournamentDivision_id = :divisionId';
          $query[1][':pId'] = $pId;
          $query[1][':divisionId'] = ($division == 'main') ? 1 : 2;
        } else {
          $query = addPlayerQuery($dbh, $player, 'player', (($division == 'main') ? 1 : 2));
        }
        $sth = $dbh->prepare($query[0]);
        $sth->execute($query[1]);
      } else {
        deletePlayer($dbh, $player, $division);
      }
    }
    if ($player->username && $player->password) {
      updateUser($dbh, $player, $ulogin);
    }
    if ($player->volunteer && $player->volunteer != 'false') {
      if (checkPlayer($dbh, $player, 'volunteer')) {
        $player->addVolunteer($dbh, 1, 'update');        
      } else {
        $player->addVolunteer($dbh);
      }
    } else {
      $player->removeVolunteer($dbh);
    }
    if ($player->newPhoto) {
      $player->setPhoto();
    }
    deNorm($dbh, 'player');
    return true;
  }
  
  function checkPlayer($dbh, $player, $division) {
    $table = ($division == 'volunteer') ? 'volunteer' : 'player';
    $field = ($division == 'volunteer') ? 'tournamentEdition_id' : 'tournamentDivision_id';
    $divisionId = ($division == 'classics') ? 2 : 1;
    $query = 'select count(*) from '.$table.' where person_id = '.$player->id.' and '.$field.' = '.$divisionId;
    $sth = $dbh->query($query);
    if ($sth->fetchColumn() > 0) {
      return true;
    } else {
      return false;
    }
  }
  
  function deletePlayer($dbh, $player, $division = 1) {
    $table = ($division == 'volunteer') ? 'volunteer' : 'player';
    $field = ($division == 'volunteer') ? 'tournamentEdition_id' : 'tournamentDivision_id';
    $delete[':divisionId'] = ($division == 'classics') ? 2 : 1;
    $delete[':person_id'] = $player->id;
    $query = 'delete from '.$table.' where person_id = :person_id and '.$field.' = :divisionId';
    $sth = $dbh->prepare($query);
    $sth->execute($delete);
  }
  
  function addPerson($dbh, $player) {
    $query = addPlayerQuery($dbh, $player, 'person');
    $sth = $dbh->prepare($query[0]);
    $sth->execute($query[1]);
    $lastIndexId = $dbh->lastInsertId();
    deNorm($dbh, 'person');
    return $lastIndexId;
  }
  
  function addVolunteer($dbh, $player, $tournament, $method = 'insert into') {
    $query = addPlayerQuery($dbh, $player, 'volunteer', $tournament, $method);
    if ($method == 'update') {
      $query[0] .= ' where person_id = :pId';
      $query[1][':pId'] = $player->id;
    }
    $sth = $dbh->prepare($query[0]);
    $sth->execute($query[1]);
    return $dbh->lastInsertId();
  }
  
  function getIdFromUser($dbh, $username) {
    $query = "select id from person where username LIKE :username";
    $select[':username'] = $username;
    $sth = $dbh->prepare($query);
    $sth->execute($select);
    $player = $sth->fetchObject('player');
    if ($player) {
      return $player->id;
    } else {
      return false;
    }
  }
  
  function updateUser($dbh, $player, $ulogin = null){
    $query = 'update person p set p.username = :username,';
    $query .= ' password = :password';
    $query .= ' where p.id = :playerId';
    $update[':username'] = $player->username;
    $update[':password'] = encrPass($player->password, $player->username);
    $update[':playerId'] = $player->id;
    $sth = $dbh->prepare($query);
    if ($sth->execute($update)) {
      if ($ulogin) {
        if ($ulogin->CreateUser($player->username,  $player->password)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    } else {
      return false;
    }
  }
  
  function salt($ind = null){
    return 'qcy0UPy5g4jC'.$ind;
  }
  
  function encrPass($pass, $ind = null, $salt = null) {
    $salt = ($salt) ? $salt : salt($ind);
    return sha1($salt.$pass);
  }
  
  function isAssoc($arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }
  
  function array_slice_assoc($array, $keys) {
    return array_intersect_key($array, array_flip($keys));
  }
   
  function getIfpaIds($dbh, $where = 'where p.ifpa_id is not null', $order = 'order by ifpa_id asc') {
    $query = 'select p.id, p.ifpa_id from person p'.getPlayerJoin();
    $sth = $dbh->query($query.' '.$where.' '.$order);
    while ($obj = $sth->fetchObject()) {
      $objs[] = $obj;
    }
    return $objs;
  }
  
  function updateIfpaRank($dbh, $player) {
    $query = 'update person p set p.ifpaRank = :ifpaRank';
    $query .= ' where p.id = :playerId';
    $update[':playerId'] = $player->id;
    $update[':ifpaRank'] = $player->rank;
    $sth = $dbh->prepare($query);
    $sth->execute($update);
  }
  
  function getTable($dbh, $ulogin, $type, $national = false) {
    $content = '
                <h1>'.ucfirst(getPlural($type)).' i Flipper-SM 2013</h1>
                  '.(($type == 'player' || $type == 'team' || $type == 'qualGroup') ? submenu2($dbh, $ulogin, 'anmalda', false).'<p>För närvarande är det '.countObjects($dbh).' spelare anmälda, varav '.countObjects($dbh, 'player', ' where tournamentDivision_id = 2 ').' i Classics och '.countObjects($dbh, 'player', ' where tournamentDivision_id = 1 and paid > 0' ).' som har betalat. Antalet dubbellag uppgår till '.countObjects($dbh, 'team', ' where tournamentDivision_id = 3 ').' stycken. För den som är intresserad så har vi sammanställt lite <a href="'.__baseHref__.'/?s=statistik">geografisk statistik</a>.</p>' : '').'
                <div id="'.$type.'Div">
                  '.(($national) ? '<input type="hidden" id="national" value="1">' : '').'
                  <span id="'.$type.'Loading"><img src="'.__baseHref__.'/images/ajax-loader.gif" alt="Loading data..."></span>
                  <table id="'.$type.'Table" class="list 
    ';
    switch ($type) {
     case 'player':
       $content .= 'registered';
     break;
     case 'game':
       $content .= 'tournamentGames';
     break;
    }
    $content .= '"></table>
                  <span id="'.$type.'All" class="getAll"></span>
                  <br />
                </div>
    ';
    return $content;
  }
  
  function getInfo($dbh, $ulogin, $type, $id, $select = true) {
    global $classes;
    if ($obj = getObjectById($dbh, $type, $id)) {
      $content = '
                  <h1>'.$obj->name.'</h1>
                  '.(($type == 'player' || $type == 'team' || $type == 'qualGroup') ? submenu2($dbh, $ulogin, 'anmalda', false, $obj) : '').'
                  <div id="infoDiv" class="infoDiv">
                    <div class="spalt" id="leftInfoDiv">
                      '.(($select) ? '<h3 id="all'.ucfirst($type).'Span">Andra '.getPlural($type).': </h3> '.createSelect(getObjectList($dbh, $type, array ('tournament' => '1', 'national' => $obj->national)), 'all'.ucfirst($type).'Select', $id).'' : '').'
      ';
      foreach($classes->{$type}->info as $field) {
        $label = '';
        if ($obj->{$field} && $obj->{$field} != '') {
          if ($classes->{$type}->fields->{$field}->type == 'select') {
            $value = getLink(getObjectById($dbh, strtolower(str_replace('parent', '', $field)), $obj->{$field.'_id'}));
          } else if ($field == 'isIfpa') {
            $value = $obj->getIfpaLink();
          } else if ($field == 'volunteer') {
            $value = ($obj->{$field}) ? 'Yes' : 'No';
          } else if ($field == 'main') {
              $qualGroup = ($obj->mainQualGroup_id) ? getQualGroupById($dbh, $obj->mainQualGroup_id) : false;
              $value = ($qualGroup) ? 'Group '.$qualGroup->getLink() : (($obj->{$field}) ? 'Yes' : 'No');
          } else if ($field == 'classics') {
            $qualGroup = ($obj->classicsQualGroup_id) ? getQualGroupById($dbh, $obj->classicsQualGroup_id) : false;
              $value = ($qualGroup) ? 'Group '.$qualGroup->getLink() : (($obj->{$field}) ? 'Yes' : 'No');
          } else if ($field == 'isIpdb') {
            $value = '<a href="http://ipdb.org/machine.cgi?id='.$obj->ipdb_id.'" target="_new">'.$obj->ipdb_id.'</a>';
          } else if ($field == 'type') {
            $divisionId = ($obj->{$field} == 'main') ? 1 : (($obj->{$field} == 'classics') ? 2 : '');
            if ($divisionId) {
              $value = '<a href="?s=object&obj='.$type.'&d='.$divisionId.'">'.ucfirst($obj->{$field}).'</a>';
            } else {
              $value = ucfirst($obj->{$field});
            }
          } else if ($field == 'rules') {
            $value = '<a href="'.$obj->{$field}.'" target="_new">Regler</a>';
          } else if ($field == 'noOfPlayers') {
            $value = $obj->getNoOfAssignedPlayers($dbh);
          } else {
            $value = ucfirst(str_replace('-00', '', $obj->{$field}));
          }
          
          $content .= '
                      <p id="'.$field.'Parahraph">
                        <span>'.(($label) ? $label : $classes->{$type}->fields->{$field}->label).':</span> '
                        .$value.'</p>
                      </p>
          ';
        }
      } 
      if ($type == 'team') {
        $players = $obj->getMembers($dbh);
        $content .= '
                      <div id="membersDiv" class="infoLabel">
                        <label>Members:</label>
        ';
        if ($players[0]) {
          foreach ($players as $player) {
            $content .= '
                        <div class="memberDiv infoValueDiv" id="'.$player->id.'memberDiv">
                          <p>'.$player->getLink().'</p>
                        </div>
            ';
          }
        }
        $content .= '</div>';
      }
      $content .= '
                  </div>
                  <div class="spalt" id="objectPhotoDiv">
                    <img class="objectPhoto" src="'.$obj->getPhoto().'" alt="'.$obj->name.'">
                  </div>
      ';
      if ($type == 'player' || $type == 'team' || $type == 'game') {
        $content .= $obj->getResults($dbh);
      }
      return $content;
    } else {
      return false;
    }
  }
  
  function getGeoFilterWheres($type = false, $gender = true) {
    global $geoTypes;
    $start = false;
    foreach(array_reverse($geoTypes) as $geoType) {
      if ($start || !$type || $type == $geoType) {
        $start = true;
        if ($filter = getGeoFilterWhere($geoType, $type)) {
          return $filter;
        }
      }
    }
    if ($gender && $filter = getGeoFilterWhere('gender')) {
      return $filter;
    }
    return false;
  }
    
  function getGeoFilterWhere($type, $origType = false) {
    $obj = ($_REQUEST['obj']) ? $_REQUEST['obj'] : '';
    $id = ($_REQUEST['id']) ? $_REQUEST['id'] : '';
    // Hard coded shit! Remove when chance given!
    if ($id == '' && preg_match('/flippersm/', __baseHref__)) {
      $obj = 'country';
      $id = 188;
    }
    if (isset($obj) && $obj == $type) {
      if (isset($id) && preg_match('/^[0-9]+$/', $id)) {
        $return = $type.'_id = '.$id;
        if ($type == 'region' || $type == 'country') {
          $parentReturn = ' or parent'.ucfirst($type).'_id = '.$id;
        }
        if ($type == $origType) {
          return $parentReturn;
        } else {
          return ' and ( '.$return.$parentReturn.' )';
        }
      } else {
        return false;
      } 
    } else {
      return false;
    }
  }  

  function getPhotoExts() {
    return array('png', 'jpg', 'jpeg', 'gif', 'PNG', 'JPG', 'JPEG', 'GIF');
  }
    
  function getPhoto($dbh, $type, $id, $thumbnail = false) {
    $obj = new $type(array('id' => $id, 'class' => $type));
    return $obj->getPhoto($thumbnail);
  }

  function setPhoto($obj) {
    return $obj->setPhoto();
  }
  
  function getLink($obj) {
    return $obj->getLink();
  }
    
  function checkField($dbh, $field, $value, $id = 0) {
    switch ($field) {
      case 'username':
        if (!preg_match('/^[a-zA-Z0-9\-_]{3,32}$/', $value)) {
          $return = array(false, '{"valid":false,"reason":"Username must be at least three character and can only include a-Z, A-Z, 0-9, dashes and underscores!","field":"'.$field.'"}');
        } else {
          $where = ' where username = "'.$value.'"';
          if ($id && $id != 0) {
            $where .= ' and id != '.$id;
          }
          $query = 'select count(*) from person '.$where;
          $sth = $dbh->query($query);
          if ($sth->fetchColumn() > 0) {
            $return = array(false, '{"valid":false,"reason":"Username is already taken!","field":"'.$field.'"}');
          } else {
            $return = array(true, '{"valid":true,"reason":"Username is up for grabs (or unchanged)!","field":"'.$field.'"}');
          }
        }
      break;
      case 'name':
        if (!preg_match('/^[a-zA-ZåäöÅÄÖüÛïÎëÊÿŸçßéÉæøÆØáÁóÓàÀČčŁłĳŠšŮ0-9 \-_#\$]{3,32}$/', $value)) {
          $return = array(false, '{"valid":false,"reason":"The name must be at least three character and can only include a-Z, A-Z, most of ÜÅÄÖ and similar, 0-9, spaces, #, $, dashes and underscores!","field":"'.$field.'", "value":"'.$value.'"}');
        } else {
          $where = ' where tournamentDivision_id = 3 and name = "'.$value.'"';
          if ($id && $id != 0) {
            $where .= ' and id != '.$id;
          }
          $query = 'select count(*) from team '.$where;
          $sth = $dbh->query($query);
          if ($sth->fetchColumn() > 0) {
            $return = array(false, '{"valid":false,"reason":"The name is already taken!","field":"'.$field.'"}');
          } else {
            $return = array(true, '{"valid":true,"reason":"The name is up for grabs (or unchanged)!","field":"'.$field.'"}');
          }
        }
      break;
      case 'mailAddress':
        if (!validEmail($value)) {
          $return = array(false, '{"valid":false,"reason":"Invalid mail address!","field":"'.$field.'"}');
        } else {
          $return = array(true, '{"valid":true,"reason":"Mail address is OK!","field":"'.$field.'"}');
        }
      break;
      case 'telephoneNumber':
        if ($value == '') {
          $return = array(true, '{"valid":true,"reason":"Phone number is OK!","field":"'.$field.'"}');
        }
      case 'mobileNumber':
        if (!preg_match('/^[0-9 \-\+\(\)]{6,}$/', $value) && !$return) {
          $return = array(false, '{"valid":false,"reason":"Please use only numbers, parantheses, spaces, dashes and plus signs!","field":"'.$field.'"}');
        } else {
          $return = array(true, '{"valid":true,"reason":"Phone number is OK!","field":"'.$field.'"}');
        }
      break;
      case 'firstName':
      case 'lastName':
      if ($value) {
          $return = array(true, '{"valid":true,"reason":"'.ucfirst($field).' is OK!","field":"'.$field.'"}');
        } else {
          $return = array(false, '{"valid":false,"reason":"'.ucfirst($field).' is required!","field":"'.$field.'"}');
        }
      break;
      case 'birthDate':
      case 'dateRegistered':
      if ($value == '' || checkdate(preg_replace('/00/','01',substr($value, 5,2)), preg_replace('/00/','01',substr($value, 8,2)), substr($value, 0,4))) {
          $return = array(true, '{"valid":true,"reason":"'.ucfirst($field).' is OK!","field":"'.$field.'"}');
        } else {
          $return = array(false, '{"valid":false,"reason":"Invalid date format - use ISO 8601 format: YYYY-MM-DD!","field":"'.$field.'"}');
        }
      break;
      case 'password':
      case 'currentPassword':
      case 'newPassword':
      case 'newPassword2':
        if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$])[0-9A-Za-z!@#$]{6,50}$/', $value)) {
          $return = array(true, '{"valid":true,"reason":"Password is OK!","field":"'.$field.'"}');
        } else {
          $return = array(false, '{"valid":false,"reason":"Password is required to be at least 6 characters, including a number, a letter and one of !@#$"}');
        }
      break;
      case 'initials':
        if (preg_match('/^[a-zA-Z0-9 \-]{1,3}$/', $value) || $value == '') {
          $return = array(true, '{"valid":true,"reason":"Initials are OK!","field":"'.$field.'", "value":"'.$value.'"}');
        } else {
          $return = array(false, '{"valid":false,"reason":"Initials can only be at most three characters without any strange ones!","field":"'.$field.'", "value":"'.$value.'"}');
        }
      break;
      case 'city':
      case 'region':
      case 'country':
      case 'continent':
      case 'gender':
        if (preg_match('/^[0-9]+$/', $value) && $value != 0) {
          $where = ' where id = "'.$value.'"';
          $query = 'select count(*) from '.$field.' '.$where;
          $sth = $dbh->query($query);
          if ($sth->fetchColumn() > 0) {
            $return = array(true, '{"valid":true,"reason":"'.ucfirst($field).' is OK!","field":"'.$field.'"}');
          } else {
            $return = array(false, '{"valid":false,"reason":"'.ucfirst($field).' ID '.$value.' doesn\'t exist!","field":"'.$field.'"}');
          }
        } else {
          if ($value || $value == 0) {
            $return = array(true, '{"valid":true,"reason":"'.ucfirst($field).' is OK!","field":"'.$field.'"}');
          } else {
            $return = array(false, '{"valid":false,"reason":"'.ucfirst($field).' is required!","field":"'.$field.'"}');
          }
        }
      break;
      case 'streetAddress':
      case 'zipCode':
      case 'main':
      case 'id':
      case 'ifpa_id':
      case 'ifpaRank':
      case 'isPlayer':
      case 'isPerson':
      case 'passwordRequired':
      case 'tournamentDivision_id':
      case 'registerPerson_id':
      case 'contactPlayer_id':
      case 'isIfpa':
      case 'class':
      case 'classics':
      case 'volunteer':
        $return = array(true, '{"valid":true,"reason":"Not checked!","field":"'.$field.'"}');
      break;
      default:
        $return = array(false, '{"valid":false,"reason":"Unknown field!","field":"'.$field.'"}');
      break;
    }
    return $return;
  }
  
  function geoFilter($objs) {
    foreach(array('city', 'region', 'country', 'continent') as $type) {
      if (isset($_REQUEST[$type.'_id'])) {
        $cmp = cmpGeo($type, $_REQUEST[$type.'_id'], true);
        $objs = array_filter($objs, $cmp);
      }
    }
    return $objs;
  }
  
  function cmpGeo($attr, $id, $type = true) {
//    debug('A: '.$attr.' ID: '.$id.' T: '.$type."\n<br />");
    return function($obj) use($attr, $id, $type) { 
      return ($obj->{$attr.'_id'} == $id) ? $type : !$type; 
    };
  }    
  
  function cmpOption($id, $type = true) {
    return function($obj) use($id, $type) { 
      return ($obj->id == $id) ? $type : !$type; 
    };
  }
  
  function cmpField($value, $type = true) {
    return function($obj) use($value, $type) { 
      return ($obj->value == $value) ? $type : !$type; 
    };
  }
  
  function locate($dbh, $obj, $type) {
    switch ($type) {
      case 'city':
        $obj->city = (isset($obj->city_id)) ? getObject($dbh, 'city', $obj->city_id) : null;
        locate($dbh, $obj, 'region');
        break;
      case 'region':
        $obj->region_id = (isset($obj->region_id)) ? $obj->region_id : ((isset($obj->city->region_id)) ? $obj->city->region_id : null);
        $obj->region = (isset($obj->region_id)) ? getObject($dbh, 'region', $obj->region_id) : null;
        locate($dbh, $obj, 'country');
      break;
      case 'country':
        $obj->country_id = (isset($obj->country_id)) ? $obj->country_id : ((isset($obj->city->country_id)) ? $obj->city->country_id : ((isset($obj->region->country_id)) ? $obj->region->country_id : null));
        $obj->country = (isset($obj->country_id)) ? getObject($dbh, 'country', $obj->country_id) : null;
        locate($dbh, $obj, 'continent');
      break;
      case 'continent':
        $obj->continent_id = (isset($obj->continent_id)) ? $obj->continent_id : ((isset($obj->city->continent_id)) ? $obj->city->continent_id : ((isset($obj->region->continent_id)) ? $obj->region->continent_id : ((isset($obj->country->continent_id)) ? $obj->country->continent_id : null)));
        $obj->continent = (isset($obj->continent_id)) ? getObject($dbh, 'continent', $obj->continent_id) : null; 
      break;
    }
  }
    
	function error ($code, $txt) {
    die ("Error! ".$code.": ".$txt);
  }
  
  function debug ($txt) {
    global $debug;
    if ($debug) {
      echo $txt."\n</br>\n";      
    }
  }
  
  function getPlural($text) {
    $plurals = array(
      'qualGroup' => 'kvalgrupper',
      'game' => 'spel',
      'machine' => 'maskiner',
      'manufacturer' => 'tillverkare',
      'gender' => 'kön',
      'person' => 'personer',
      'player' => 'spelare',
      'city' => 'städer',
      'region' => 'landskap',
      'country' => 'länder',
      'continent' => 'kontinenter',
      'team' => 'lag'
    );
    return $plurals[$text];    
  }
  
  function getDataTables($identifier = 'table') {
    if($_SERVER['SCRIPT_NAME'] == '/adminTools.php') {
      switch($identifier) {
        case '.scores':
          $sorting = "'aaSorting': [[0, 'desc' ], [2, 'asc']],";
          $sort = "
            'aoColumns': [
              {'sType': 'numeric-empty-last' },
              null,
              {'sType': 'numeric-empty-last' },
              {'sType': 'numeric-empty-last' }
            ],
          ";
        break;
        case '.standings':
          $sorting = "'aaSorting': [[0, 'desc' ], [2, 'asc']],";
          $sort = "
            'aoColumns': [
              {'sType': 'numeric-empty-last' },
              null,
              null,
              {'sType': 'numeric-empty-last' }
            ],
          ";
        break;
        case '.mainStandings':
          $sorting = "'aaSorting': [[0, 'desc' ], [2, 'asc']],";
          $sort = "
            'aoColumns': [
              {'sType': 'numeric-empty-last' },
              null,
              null,
              {'sType': 'numeric-empty-last' },
              null
            ],
          ";
        break;
        case '.listView':
          $sorting = "'aaSorting': [[0, 'desc' ], [2, 'asc']],";
          $sort = "
            'aoColumns': [
              {'sType': 'numeric-empty-last' },
              {'sType': 'numeric-empty-last' },
              null,
              null,
              null,
              null
            ],
          ";
        break;
        default:
          $sorting = null;
          $sort = null;
      }

      return "
          <script type=\"text/javascript\">
            $(document).ready(function() {
             $.fn.dataTableExt.oSort['numeric-empty-last-asc'] = function(a,b) {
                var x = a.replace( /^$/, 200000);
                var y = b.replace( /^$/, 200000);
                x = parseFloat( x );
                y = parseFloat( y );
                return ((x < y) ?  1 : ((x > y) ? -1 : 0));
              };
              $.fn.dataTableExt.oSort['numeric-empty-last-desc'] = function(a,b) {
                var y = a.replace( /^$/, 200000);
                var x = b.replace( /^$/, 200000);
                x = parseFloat( x );
                y = parseFloat( y );
                return ((x < y) ?  1 : ((x > y) ? -1 : 0));
              };
              $('".$identifier."').dataTable({
                'bProcessing': true,
                'bDestroy': true,
                'bJQueryUI': true,
                'sPaginationType': 'full_numbers',
                'iDisplayLength': 300,
                ".$sorting."
                ".$sort."
                'aLengthMenu': [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]
              });
            });
            $(function() {
              $('#tabs').tabs();
            });
          </script>
      ";
    } else {
      return "
          <script type=\"text/javascript\">
            $(document).ready(function() {
              $('#tabs').tabs();
              $('".$identifier."').tablesorter();
            });
            $('#tab_links a').click(function() {
	            var section_to_show = $(this).attr('href');
             	hide_all_sections();
	            $(section_to_show).removeClass('hidden');
	            return false;
            });
            function hide_all_sections() {
	            for (var n=0;n<$('.section').length;n++) {
                $('.section').eq(n).addClass('hidden');
              }
            }
          </script>
      ";
    }
  }
  
?>
