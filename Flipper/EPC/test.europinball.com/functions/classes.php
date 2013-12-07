<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
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
    return new cities($data, $search);
  }

  function continent($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new continent($data, $search, $depth);
    return obj($obj);
  }

  function continents($data = NULL, $search = NULL) {
    return new continents($data, $search);
  }

  function country($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new country($data, $search, $depth);
    return obj($obj);
  }

  function countries($data = NULL, $search = NULL) {
    return new countries($data, $search);
  }

  function division($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new division($data, $search, $depth);
    return obj($obj);
  }

  function divisions($data = NULL, $prop = NULL) {
    return new divisions($data, $prop);
  }

  function entry($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new entry($data, $search, $depth);
    return obj($obj);
  }

  function entries($data = NULL, $prop = NULL) {
    return new entries($data, $prop);
  }

  function game($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new game($data, $search, $depth);
    return obj($obj);
  }

  function games($data = NULL, $search = NULL) {
    return new games($data, $search);
  }

  function gender($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new gender($data, $search, $depth);
    return obj($obj);
  }

  function genders($data = NULL, $search = NULL) {
    return new genders($data, $search);
  }

  function location($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new location($data, $search, $depth);
    return obj($obj);
  }

  function locations($data = NULL, $prop = NULL) {
    return new locations($data, $prop);
  }

  function machine($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new machine($data, $search, $depth);
    return obj($obj);
  }

  function machines($data = NULL, $search = NULL) {
    return new machines($data, $search);
  }

  function manufacturer($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new manufacturer($data, $search, $depth);
    return obj($obj);
  }

  function manufacturers($data = NULL, $search = NULL) {
    return new manufacturers($data, $search);
  }

  function match($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new match($data, $search, $depth);
    return obj($obj);
  }

  function matches($data = NULL, $search = NULL) {
    return new matches($data, $search);
  }

  function matchPlayer($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new matchPlayer($data, $search, $depth);
    return obj($obj);
  }

  function matchPlayers($data = NULL, $search = NULL) {
    return new matchPlayers($data, $search);
  }

  function owner($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new owner($data, $search, $depth);
    return obj($obj);
  }
  
  function owners($data = NULL, $search = NULL) {
    return new owners($data, $search);
  }
  
  function period($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new period($data, $search, $depth);
    return obj($obj);
  }

  function periods($data = NULL, $search = NULL) {
    return new periods($data, $search);
  }

  function person($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new person($data, $search, $depth);
    return obj($obj);
  }

  function persons($data = NULL, $search = NULL) {
    return new persons($data, $search);
  }

  function player($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new player($data, $search, $depth);
    return obj($obj);
  }

  function players($data = NULL, $search = NULL) {
    return new players($data, $search);
  }

  function qualGroup($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new qualGroup($data, $search, $depth);
    return obj($obj);
  }

  function qualGroups($data = NULL, $search = NULL) {
    return new qualGroups($data, $search);
  }

  function region($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new region($data, $search, $depth);
    return obj($obj);
  }

  function regions($data = NULL, $search = NULL) {
    return new regions($data, $search);
  }

  function score($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new score($data, $search, $depth);
    return obj($obj);
  }

  function scores($data = NULL, $search = NULL) {
    return new scores($data, $search);
  }

  function set($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new set($data, $search, $depth);
    return obj($obj);
  }

  function sets($data = NULL, $search = NULL) {
    return new sets($data, $search);
  }

  function task($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new task($data, $search, $depth);
    return obj($obj);
  }

  function tasks($data = NULL, $search = NULL) {
    return new tasks($data, $search);
  }

  function team($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new team($data, $search, $depth);
    return obj($obj);
  }

  function teams($data = NULL, $search = NULL) {
    return new teams($data, $search);
  }

  function tournament($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new tournament($data, $search, $depth);
    return obj($obj);
  }

  function tournaments($data = NULL, $prop = NULL) {
    return new tournaments($data, $prop);
  }

  function tshirt($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new tshirt($data, $search, $depth);
    return obj($obj);
  }

  function tshirts($data = NULL, $search = NULL) {
    return new tshirts($data, $search);
  }

  function volunteer($data = NULL, $search = NOSEARCH, $depth = NULL) {
    $obj = new volunteer($data, $search, $depth);
    return obj($obj);
  }

  function volunteers($data = NULL, $search = NULL) {
    return new volunteers($data, $search);
  }

  function isGroup($group, $nostring = FALSE) {
    $group = (is_string($group) && !$nostring) ? new $group() : $group;
    return $group instanceof group;
  }

  function isGeo($obj, $nostring = FALSE) {
    $obj = (is_string($obj) && !$nostring) ? new $obj() : $obj;
    return $obj instanceof geography;
  }
  
  function isObj($obj, $nostring = FALSE) {
    $obj = (is_string($obj) && !$nostring) ? new $obj() : $obj;
    return $obj instanceof base;
  }
  
  function isId($id) {
    return (is_int($id) || is_string($id)) ? preg_match('/^[0-9]+$/', $id) : FALSE;
  }
  
?>