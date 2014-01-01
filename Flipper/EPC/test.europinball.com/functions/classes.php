<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    } else if (is_file(__ROOT__.'/classes/html/'.$class.'.class.php')) {
      include __ROOT__.'/classes/html/'.$class.'.class.php';
    }
  });
  
  function obj($class = 'base', $data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    $obj = new $class($data, $search, $depth);
    return ($obj->failed) ? FALSE : $obj;
  }

  function objs($class = 'group', $data = NULL, $search = NULL, $cond = 'and') {
    if ($data == 'all') {
      if (!$clas::$all){
        $class::$all = new $class($data, $search, $cond);
      }
      return $class::$all;
    } else if ($data === FALSE) {
      return FALSE;
    } else {
      return new $class($data, $search, $cond);
    }
  }

  function city($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function cities($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }
  
  function isCity($city, $string = FALSE) {
    $city = (is_string($city) && class_exists($city) && $string) ? new $city() : $city;
    return (isObj($city) && get_class($city) == 'city');
  }

  function isCities($cities) {
    return (isGroup($cities) && get_class($cities) == 'cities');
  }

  function color($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function colors($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }
  
  function isColor($city, $string = FALSE) {
    $city = (is_string($city) && class_exists($city) && $string) ? new $city() : $city;
    return (isObj($color) && get_class($color) == 'color');
  }

  function isColors($colors) {
    return (isGroup($colors) && get_class($colors) == 'colors');
  }

  function continent($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function continents($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isContinent($continent, $string = FALSE) {
    $continent = (is_string($continent) && class_exists($continent) && $string) ? new $continent() : $continent;
    return (isObj($continent) && get_class($continent) == 'continent');
  }

  function isContinents($continents) {
    return (isGroup($continents) && get_class($continents) == 'continents');
  }

  function country($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function countries($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isCountry($country, $string = FALSE) {
    $country = (is_string($country) && class_exists($country) && $string) ? new $country() : $country;
    return (isObj($country) && get_class($country) == 'country');
  }

  function isCountries($countries) {
    return (isGroup($countries) && get_class($countries) == 'countries');
  }

  function division($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function divisions($data = NULL, $prop = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isDivision($division, $string = FALSE) {
    $division = (is_string($division) && class_exists($division) && $string) ? new $division() : $division;
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
  
  function entry($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function entries($data = NULL, $prop = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isEntry($entry, $string = FALSE) {
    $entry = (is_string($entry) && class_exists($entry) && $string) ? new $entry() : $entry;
    return (isObj($entry) && get_class($entry) == 'entry');
  }

  function isEntries($entries) {
    return (isGroup($entries) && get_class($entries) == 'entries');
  }
  
  function game($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function games($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isGame($game, $string = FALSE) {
    $game = (is_string($game) && class_exists($game) && $string) ? new $game() : $game;
    return (isObj($game) && get_class($game) == 'game');
  }

  function isGames($games) {
    return (isGroup($games) && get_class($games) == 'games');
  }
  
  function gender($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function genders($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isGender($gender, $string = FALSE) {
    $gender = (is_string($gender) && class_exists($gender) && $string) ? new $gender() : $gender;
    return (isObj($gender) && get_class($gender) == 'gender');
  }

  function isGenders($genders) {
    return (isGroup($genders) && get_class($genders) == 'genders');
  }
  
  function location($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function locations($data = NULL, $prop = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isLocation($location, $string = FALSE) {
    $location = (is_string($location) && class_exists($location) && $string) ? new $location() : $location;
    return (isObj($location) && get_class($location) == 'location');
  }

  function isLocations($locations) {
    return (isGroup($locations) && get_class($locations) == 'locations');
  }
  
  function machine($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function machines($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isMachine($machine, $string = FALSE) {
    $machine = (is_string($machine) && class_exists($machine) && $string) ? new $machine() : $machine;
    return (isObj($machine) && get_class($machine) == 'machine');
  }

  function isMachines($machines) {
    return (isGroup($machines) && get_class($machines) == 'machines');
  }
  
  function manufacturer($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function manufacturers($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isManufacturer($manufacturer, $string = FALSE) {
    $manufacturer = (is_string($manufacturer) && class_exists($manufacturer) && $string) ? new $manufacturer() : $manufacturer;
    return (isObj($manufacturer) && get_class($manufacturer) == 'manufacturer');
  }

  function isManufacturers($manufacturers) {
    return (isGroup($manufacturers) && get_class($manufacturers) == 'manufacturers');
  }
  
  function match($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function matches($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isMatch($match, $string = FALSE) {
    $match = (is_string($match) && class_exists($match) && $string) ? new $match() : $match;
    return (isObj($match) && get_class($match) == 'match');
  }

  function isMatches($matches) {
    return (isGroup($matches) && get_class($matches) == 'matches');
  }
  
  function matchPlayer($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function matchPlayers($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isMatchPlayer($matchPlayer, $string = FALSE) {
    $matchPlayer = (is_string($matchPlayer) && class_exists($matchPlayer) && $string) ? new $matchPlayer() : $matchPlayer;
    return (isObj($matchPlayer) && get_class($matchPlayer) == 'matchPlayer');
  }

  function isMatchPlayers($matchPlayers) {
    return (isGroup($matchPlayers) && get_class($matchPlayers) == 'matchPlayers');
  }
  
  function owner($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }
  
  function owners($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }
  
  function isOwner($owner, $string = FALSE) {
    $owner = (is_string($owner) && class_exists($owner) && $string) ? new $owner() : $owner;
    return (isObj($owner) && get_class($owner) == 'owner');
  }

  function isOwners($owners) {
    return (isGroup($owners) && get_class($owners) == 'owners');
  }
  
  function period($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function periods($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isPeriod($period, $string = FALSE) {
    $period = (is_string($period) && class_exists($period) && $string) ? new $period() : $period;
    return (isObj($period) && get_class($period) == 'period');
  }

  function isPeriods($periods) {
    return (isGroup($periods) && get_class($periods) == 'periods');
  }
  
  function person($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function persons($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isPerson($person, $string = FALSE) {
    $person = (is_string($person) && class_exists($person) && $string) ? new $person() : $person;
    return (isObj($person) && get_class($person) == 'person');
  }

  function isPersons($persons) {
    return (isGroup($persons) && get_class($persons) == 'persons');
  }
  
  function player($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function players($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isPlayer($player, $string = FALSE) {
    $player = (is_string($player) && class_exists($player) && $string) ? new $player() : $player;
    return (isObj($player) && get_class($player) == 'player');
  }

  function isPlayers($players) {
    return (isGroup($players) && get_class($players) == 'players');
  }
  
  function qualGroup($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function qualGroups($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isQualGroup($qualGroup, $string = FALSE) {
    $qualGroup = (is_string($qualGroup) && class_exists($qualGroup) && $string) ? new $qualGroup() : $qualGroup;
    return (isObj($qualGroup) && get_class($qualGroup) == 'qualGroup');
  }

  function isQualGroups($qualGroups) {
    return (isGroup($qualGroups) && get_class($qualGroups) == 'qualGroups');
  }
  
  function region($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function regions($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isRegion($region, $string = FALSE) {
    $region = (is_string($region) && class_exists($region) && $string) ? new $region() : $region;
    return (isObj($region) && get_class($region) == 'region');
  }

  function isRegions($regions) {
    return (isGroup($regions) && get_class($regions) == 'regions');
  }
  
  function score($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function scores($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isScore($score, $string = FALSE) {
    $score = (is_string($score) && class_exists($score) && $string) ? new $score() : $score;
    return (isObj($score) && get_class($score) == 'score');
  }

  function isScores($scores) {
    return (isGroup($scores) && get_class($scores) == 'scores');
  }
  
  function set($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function sets($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isMatchSet($set, $string = FALSE) {
    $set = (is_string($set) && class_exists($set) && $string) ? new $set() : $set;
    return (isObj($set) && get_class($set) == 'set');
  }

  function isSets($sets) {
    return (isGroup($sets) && get_class($sets) == 'sets');
  }
  
  function task($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function tasks($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isTask($task, $string = FALSE) {
    $task = (is_string($task) && class_exists($task) && $string) ? new $task() : $task;
    return (isObj($task) && get_class($task) == 'task');
  }

  function isTasks($tasks) {
    return (isGroup($tasks) && get_class($tasks) == 'tasks');
  }
  
  function team($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function teams($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isTeam($team, $string = FALSE) {
    $team = (is_string($team) && class_exists($team) && $string) ? new $team() : $team;
    return (isObj($team) && get_class($team) == 'team');
  }

  function isTeams($teams) {
    return (isGroup($teams) && get_class($teams) == 'teams');
  }
  
  function tournament($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function tournaments($data = NULL, $prop = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isTournament($tournament, $string = FALSE) {
    $tournament = (is_string($tournament) && class_exists($tournament) && $string) ? new $tournament() : $tournament;
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

  function tshirt($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function tshirts($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isTshirt($tshirt, $string = FALSE) {
    $tshirt = (is_string($tshirt) && class_exists($tshirt) && $string) ? new $tshirt() : $tshirt;
    return (isObj($tshirt) && get_class($tshirt) == 'tshirt');
  }

  function isTshirts($tshirts) {
    return (isGroup($tshirts) && get_class($tshirts) == 'tshirts');
  }
  
  function tshirtOrder($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function tshirtOrders($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isTshirtOrder($tshirtOrder, $string = FALSE) {
    $tshirtOrder = (is_string($tshirtOrder) && class_exists($tshirtOrder) && $string) ? new $tshirtOrder() : $tshirtOrder;
    return (isObj($tshirtOrder) && get_class($tshirtOrder) == 'tshirtOrder');
  }

  function isTshirtOrders($tshirtOrders) {
    return (isGroup($tshirtOrders) && get_class($tshirtOrders) == 'tshirtOrders');
  }
  
  function volunteer($data = NULL, $search = config::NOSEARCH, $depth = config::$parentDepth) {
    return obj(__FUNCTION__, $data, $search, $depth);
  }

  function volunteers($data = NULL, $search = NULL, $cond = 'and') {
    return objs(__FUNCTION__, $data, $search, $cond);
  }

  function isVolunteer($volunteer, $string = FALSE) {
    $volunteer = (is_string($volunteer) && class_exists($volunteer) && $string) ? new $volunteer() : $volunteer;
    return (isObj($volunteer) && get_class($volunteer) == 'volunteer');
  }

  function isVolunteers($volunteers) {
    return (isGroup($volunteers) && get_class($volunteers) == 'volunteers');
  }
  
  function isGroup($group, $string = FALSE) {
    $group = (is_string($group) && class_exists($group) && $string) ? new $group() : $group;
    return $group instanceof group;
  }

  function isGeoGroup($group, $string = FALSE) {
    $group = (is_string($group) && class_exists($group) && $string) ? new $group() : $group;
    return $group instanceof geoGroup;
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