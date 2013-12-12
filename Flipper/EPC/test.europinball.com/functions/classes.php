<?php

  spl_autoload_register(function($class) {
    echo "__ROOT__.'/classes/html/'.$class.'.class.php'";
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    } else if (is_file(__ROOT__.'/classes/html/'.$class.'.class.php')) {
      include __ROOT__.'/classes/html/'.$class.'.class.php';
      echo "found";
    }
  });
  
  function obj($obj) {
    return ($obj->failed) ? FALSE : $obj;
  }

  function city($data = NULL, $search = NOSEARCH, $depth = NULL) {
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

  function continent($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($continents) && get_class($continents) == 'continents');
  }

  function country($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($countries) && get_class($countries) == 'countries');
  }

  function division($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($divisions) && get_class($divisions) == 'divisions');
  }
  
  function entry($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
  
  function game($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($games) && get_class($games) == 'games');
  }
  
  function gender($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($genders) && get_class($genders) == 'genders');
  }
  
  function location($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($locations) && get_class($locations) == 'locations');
  }
  
  function machine($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($machines) && get_class($machines) == 'machines');
  }
  
  function manufacturer($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($manufacturers) && get_class($manufacturers) == 'manufacturers');
  }
  
  function match($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($matches) && get_class($matches) == 'matches');
  }
  
  function matchPlayer($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($matchPlayers) && get_class($matchPlayers) == 'matchPlayers');
  }
  
  function owner($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($owners) && get_class($owners) == 'owners');
  }
  
  function period($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($periods) && get_class($periods) == 'periods');
  }
  
  function person($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($persons) && get_class($persons) == 'persons');
  }
  
  function player($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($players) && get_class($players) == 'players');
  }
  
  function qualGroup($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($qualGroups) && get_class($qualGroups) == 'qualGroups');
  }
  
  function region($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($regions) && get_class($regions) == 'regions');
  }
  
  function score($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($scores) && get_class($scores) == 'scores');
  }
  
  function set($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($sets) && get_class($sets) == 'sets');
  }
  
  function task($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($tasks) && get_class($tasks) == 'tasks');
  }
  
  function team($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($teams) && get_class($teams) == 'teams');
  }
  
  function tournament($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($tournaments) && get_class($tournaments) == 'tournaments');
  }
  
  function tshirt($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($tshirts) && get_class($tshirts) == 'tshirts');
  }
  
  function volunteer($data = NULL, $search = NOSEARCH, $depth = NULL) {
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
    return (isObj($volunteers) && get_class($volunteers) == 'volunteers');
  }
  
  function isGroup($group, $nostring = FALSE) {
    $group = (is_string($group) && class_exists($group) && !$nostring) ? new $group() : $group;
    return $group instanceof group;
  }

  function isGeo($obj, $nostring = FALSE) {
    $obj = (is_string($obj) && class_exists($obj) && !$nostring) ? new $obj() : $obj;
    return $obj instanceof geography;
  }
  
  function isObj($obj, $nostring = FALSE) {
    $obj = (is_string($obj) && class_exists($obj) && !$nostring && class_exists($obj)) ? new $obj() : $obj;
    return $obj instanceof base;
  }
  
  function isHtml($obj, $nostring = FALSE) {
    $obj = (is_string($obj) && class_exists($obj) && !$nostring && class_exists($obj)) ? new $obj() : $obj;
    return $obj instanceof html;
  }
  
  function isId($id) {
    return ((is_int($id) || is_string($id))) ? preg_match('/^[0-9]+$/', $id) : FALSE;
  }
  
?>