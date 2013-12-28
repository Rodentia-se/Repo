<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    } else if (is_file(__ROOT__.'/classes/html/'.$class.'.class.php')) {
      include __ROOT__.'/classes/html/'.$class.'.class.php';
    }
  });
  
  function obj($obj) {
    return ($obj->failed) ? FALSE : $obj;
  }

  function city($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new city($data, $search, $depth);
    return obj($obj);
  }

  function cities($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new cities($data, $search);
  }
  
  function isCity($city) {
    return (isObj($city) && get_class($city) == 'city');
  }

  function isCities($cities) {
    return (isGroup($cities) && get_class($cities) == 'cities');
  }

  function color($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new color($data, $search, $depth);
    return obj($obj);
  }

  function colors($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new colors($data, $search);
  }
  
  function isColor($color) {
    return (isObj($color) && get_class($color) == 'color');
  }

  function isColors($colors) {
    return (isGroup($colors) && get_class($colors) == 'colors');
  }

  function continent($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new continent($data, $search, $depth);
    return obj($obj);
  }

  function continents($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new continents($data, $search);
  }

  function isContinent($continent) {
    return (isObj($continent) && get_class($continent) == 'continent');
  }

  function isContinents($continents) {
    return (isGroup($continents) && get_class($continents) == 'continents');
  }

  function country($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new country($data, $search, $depth);
    return obj($obj);
  }

  function countries($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new countries($data, $search);
  }

  function isCountry($country) {
    return (isObj($country) && get_class($country) == 'country');
  }

  function isCountries($countries) {
    return (isGroup($countries) && get_class($countries) == 'countries');
  }

  function division($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new division($data, $search, $depth);
    return obj($obj);
  }

  function divisions($data = NULL, $prop = NULL) {
    return ($data === FALSE) ? FALSE : new divisions($data, $prop);
  }

  function isDivision($division) {
    return (isObj($division) && get_class($division) == 'division');
  }

  function isDivisions($divisions) {
    return (isGroup($divisions) && get_class($divisions) == 'divisions');
  }
  
  function getDivision($obj = 'main') {
    $obj = ($obj) ? $obj : 'main';
    $division = division($obj);
    if (isDivision($division)) {
      return $division;
    }
    $tournament = tournament($obj);
    if (isTournament($tournament)) {
      $division = division($tournament);
      if (isDivision($division)) {
        return $division;
      }
    }
    $division = division('main');
    if (isDivision($division)) {
      return $division;
    }
    return FALSE;
  }
  
  function entry($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new entry($data, $search, $depth);
    return obj($obj);
  }

  function entries($data = NULL, $prop = NULL) {
    return ($data === FALSE) ? FALSE : new entries($data, $prop);
  }

  function isEntry($entry) {
    return (isObj($entry) && get_class($entry) == 'entry');
  }

  function isEntries($entries) {
    return (isGroup($entries) && get_class($entries) == 'entries');
  }
  
  function game($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new game($data, $search, $depth);
    return obj($obj);
  }

  function games($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new games($data, $search);
  }

  function isGame($game) {
    return (isObj($game) && get_class($game) == 'game');
  }

  function isGames($games) {
    return (isGroup($games) && get_class($games) == 'games');
  }
  
  function gender($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new gender($data, $search, $depth);
    return obj($obj);
  }

  function genders($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new genders($data, $search);
  }

  function isGender($gender) {
    return (isObj($gender) && get_class($gender) == 'gender');
  }

  function isGenders($genders) {
    return (isGroup($genders) && get_class($genders) == 'genders');
  }
  
  function location($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new location($data, $search, $depth);
    return obj($obj);
  }

  function locations($data = NULL, $prop = NULL) {
    return ($data === FALSE) ? FALSE : new locations($data, $prop);
  }

  function isLocation($location) {
    return (isObj($location) && get_class($location) == 'location');
  }

  function isLocations($locations) {
    return (isGroup($locations) && get_class($locations) == 'locations');
  }
  
  function machine($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new machine($data, $search, $depth);
    return obj($obj);
  }

  function machines($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new machines($data, $search);
  }

  function isMachine($machine) {
    return (isObj($machine) && get_class($machine) == 'machine');
  }

  function isMachines($machines) {
    return (isGroup($machines) && get_class($machines) == 'machines');
  }
  
  function manufacturer($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new manufacturer($data, $search, $depth);
    return obj($obj);
  }

  function manufacturers($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new manufacturers($data, $search);
  }

  function isManufacturer($manufacturer) {
    return (isObj($manufacturer) && get_class($manufacturer) == 'manufacturer');
  }

  function isManufacturers($manufacturers) {
    return (isGroup($manufacturers) && get_class($manufacturers) == 'manufacturers');
  }
  
  function match($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new match($data, $search, $depth);
    return obj($obj);
  }

  function matches($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new matches($data, $search);
  }

  function isMatch($match) {
    return (isObj($match) && get_class($match) == 'match');
  }

  function isMatches($matches) {
    return (isGroup($matches) && get_class($matches) == 'matches');
  }
  
  function matchPlayer($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new matchPlayer($data, $search, $depth);
    return obj($obj);
  }

  function matchPlayers($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new matchPlayers($data, $search);
  }

  function isMatchPlayer($matchPlayer) {
    return (isObj($matchPlayer) && get_class($matchPlayer) == 'matchPlayer');
  }

  function isMatchPlayers($matchPlayers) {
    return (isGroup($matchPlayers) && get_class($matchPlayers) == 'matchPlayers');
  }
  
  function owner($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new owner($data, $search, $depth);
    return obj($obj);
  }
  
  function owners($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new owners($data, $search);
  }
  
  function isOwner($owner) {
    return (isObj($owner) && get_class($owner) == 'owner');
  }

  function isOwners($owners) {
    return (isGroup($owners) && get_class($owners) == 'owners');
  }
  
  function period($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new period($data, $search, $depth);
    return obj($obj);
  }

  function periods($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new periods($data, $search);
  }

  function isPeriod($period) {
    return (isObj($period) && get_class($period) == 'period');
  }

  function isPeriods($periods) {
    return (isGroup($periods) && get_class($periods) == 'periods');
  }
  
  function person($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new person($data, $search, $depth);
    return obj($obj);
  }

  function persons($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new persons($data, $search);
  }

  function isPerson($person) {
    return (isObj($person) && get_class($person) == 'person');
  }

  function isPersons($persons) {
    return (isGroup($persons) && get_class($persons) == 'persons');
  }
  
  function player($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new player($data, $search, $depth);
    return obj($obj);
  }

  function players($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new players($data, $search);
  }

  function isPlayer($player) {
    return (isObj($player) && get_class($player) == 'player');
  }

  function isPlayers($players) {
    return (isGroup($players) && get_class($players) == 'players');
  }
  
  function qualGroup($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new qualGroup($data, $search, $depth);
    return obj($obj);
  }

  function qualGroups($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new qualGroups($data, $search);
  }

  function isQualGroup($qualGroup) {
    return (isObj($qualGroup) && get_class($qualGroup) == 'qualGroup');
  }

  function isQualGroups($qualGroups) {
    return (isGroup($qualGroups) && get_class($qualGroups) == 'qualGroups');
  }
  
  function region($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new region($data, $search, $depth);
    return obj($obj);
  }

  function regions($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new regions($data, $search);
  }

  function isRegion($region) {
    return (isObj($region) && get_class($region) == 'region');
  }

  function isRegions($regions) {
    return (isGroup($regions) && get_class($regions) == 'regions');
  }
  
  function score($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new score($data, $search, $depth);
    return obj($obj);
  }

  function scores($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new scores($data, $search);
  }

  function isScore($score) {
    return (isObj($score) && get_class($score) == 'score');
  }

  function isScores($scores) {
    return (isGroup($scores) && get_class($scores) == 'scores');
  }
  
  function set($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new set($data, $search, $depth);
    return obj($obj);
  }

  function sets($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new sets($data, $search);
  }

  function isMatchSet($set) {
    return (isObj($set) && get_class($set) == 'set');
  }

  function isSets($sets) {
    return (isGroup($sets) && get_class($sets) == 'sets');
  }
  
  function task($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new task($data, $search, $depth);
    return obj($obj);
  }

  function tasks($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new tasks($data, $search);
  }

  function isTask($task) {
    return (isObj($task) && get_class($task) == 'task');
  }

  function isTasks($tasks) {
    return (isGroup($tasks) && get_class($tasks) == 'tasks');
  }
  
  function team($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new team($data, $search, $depth);
    return obj($obj);
  }

  function teams($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new teams($data, $search);
  }

  function isTeam($team) {
    return (isObj($team) && get_class($team) == 'team');
  }

  function isTeams($teams) {
    return (isGroup($teams) && get_class($teams) == 'teams');
  }
  
  function tournament($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new tournament($data, $search, $depth);
    return obj($obj);
  }

  function tournaments($data = NULL, $prop = NULL) {
    return ($data === FALSE) ? FALSE : new tournaments($data, $prop);
  }

  function isTournament($tournament) {
    return (isObj($tournament) && get_class($tournament) == 'tournament');
  }

  function isTournaments($tournaments) {
    return (isGroup($tournaments) && get_class($tournaments) == 'tournaments');
  }
  
  function getTournament($obj = 'active') {
    $obj = ($obj) ? $obj : 'active';
    $tournament = tournament($obj);
    if (isTournament($tournament)) {
      return $tournament;
    }
    $tournament = tournament('active');
    if (isTournament($tournament)) {
      return $tournament;
    }
    $tournament = tournament('current');
    if (isTournament($tournament)) {
      return $tournament;
    }
    return FALSE;
  }

  function tshirt($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new tshirt($data, $search, $depth);
    return obj($obj);
  }

  function tshirts($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new tshirts($data, $search);
  }

  function isTshirt($tshirt) {
    return (isObj($tshirt) && get_class($tshirt) == 'tshirt');
  }

  function isTshirts($tshirts) {
    return (isGroup($tshirts) && get_class($tshirts) == 'tshirts');
  }
  
  function tshirtOrder($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new tshirtOrder($data, $search, $depth);
    return obj($obj);
  }

  function tshirtOrders($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new tshirtOrders($data, $search);
  }

  function isTshirtOrder($tshirtOrder) {
    return (isObj($tshirtOrder) && get_class($tshirtOrder) == 'tshirtOrder');
  }

  function isTshirtOrders($tshirtOrders) {
    return (isGroup($tshirtOrders) && get_class($tshirtOrders) == 'tshirtOrders');
  }
  
  function volunteer($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
    $obj = new volunteer($data, $search, $depth);
    return obj($obj);
  }

  function volunteers($data = NULL, $search = NULL) {
    return ($data === FALSE) ? FALSE : new volunteers($data, $search);
  }

  function isVolunteer($volunteer) {
    return (isObj($volunteer) && get_class($volunteer) == 'volunteer');
  }

  function isVolunteers($volunteers) {
    return (isGroup($volunteers) && get_class($volunteers) == 'volunteers');
  }
  
  function isGroup($group, $string = FALSE) {
    $group = (is_string($group) && class_exists($group) && $string) ? new $group() : $group;
    return $group instanceof group;
  }

  function isGeo($obj, $string = FALSE) {
    $obj = (is_string($obj) && class_exists($obj) && $string) ? new $obj() : $obj;
    return $obj instanceof geography;
  }
  
  function isObj($obj, $string = FALSE) {
    $obj = (is_string($obj) && class_exists($obj) && $string) ? new $obj() : $obj;
    return $obj instanceof base;
  }
  
  function isHtml($obj, $string = FALSE) {
    $obj = (is_string($obj) && class_exists($obj) && $string) ? new $obj() : $obj;
    return $obj instanceof html;
  }
  
  function isId($id) {
    return ((is_int($id) || is_string($id))) ? preg_match('/^[0-9]+$/', $id) : FALSE;
  }
  
?>