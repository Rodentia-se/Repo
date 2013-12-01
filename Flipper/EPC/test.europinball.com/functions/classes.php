<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
    }
  });
  
  function object($obj) {
    if ($obj->failed) {
      return FALSE;
    } else {
      return $obj;
    }
  }

  function city($data = NULL, $search = NULL) {
    $obj = new city($data, $search);
    return object($obj);
  }

  function cities($data = NULL, $search = NULL) {
    $obj = new cities($data, $search);
    return object($obj);
  }

  function continent($data = NULL, $search = NULL) {
    $obj = new continent($data, $search);
    return object($obj);
  }

  function continents($data = NULL, $search = NULL) {
    $obj = new continents($data, $search);
    return object($obj);
  }

  function country($data = NULL, $search = NULL) {
    $obj = new country($data, $search);
    return object($obj);
  }

  function countries($data = NULL, $search = NULL) {
    $obj = new countries($data, $search);
    return object($obj);
  }

  function division($data = NULL, $search = NULL) {
    $obj = new division($data, $search);
    return object($obj);
  }

  function divisions($data = NULL, $prop = NULL) {
    $objs = new divisions($data, $prop);
    return object($obj);
  }

  function entry($data = NULL, $search = NULL) {
    $obj = new entry($data, $search);
    return object($obj);
  }

  function entries($data = NULL, $prop = NULL) {
    $obj = new entries($data, $prop);
    return object($obj);
  }

  function game($data = NULL, $search = NULL) {
    $obj = new game($data, $search);
    return object($obj);
  }

  function games($data = NULL, $search = NULL) {
    $obj = new games($data, $search);
    return object($obj);
  }

  function gender($data = NULL, $search = NULL) {
    $obj = new gender($data, $search);
    return object($obj);
  }

  function genders($data = NULL, $search = NULL) {
    $obj = new genders($data, $search);
    return object($obj);
  }

  function location($data = NULL, $search = NULL) {
    $obj = new location($data, $search);
    return object($obj);
  }

  function locations($data = NULL, $prop = NULL) {
    $obj = new locations($data, $prop);
    return object($obj);
  }

  function machine($data = NULL, $search = NULL) {
    $obj = new machine($data, $search);
    return object($obj);
  }

  function machines($data = NULL, $search = NULL) {
    $obj = new machines($data, $search);
    return object($obj);
  }

  function manufacturer($data = NULL, $search = NULL) {
    $obj = new manufacturer($data, $search);
    return object($obj);
  }

  function manufacturers($data = NULL, $search = NULL) {
    $obj = new manufacturers($data, $search);
    return object($obj);
  }

  function match($data = NULL, $search = NULL) {
    $obj = new match($data, $search);
    return object($obj);
  }

  function matches($data = NULL, $search = NULL) {
    $obj = new matches($data, $search);
    return object($obj);
  }

  function matchPlayer($data = NULL, $search = NULL) {
    $obj = new matchPlayer($data, $search);
    return object($obj);
  }

  function matchPlayers($data = NULL, $search = NULL) {
    $obj = new matchPlayers($data, $search);
    return object($obj);
  }

  function owner($data = NULL, $search = NULL) {
    $obj = new owner($data, $search);
    return object($obj);
  }
  
  function owners($data = NULL, $search = NULL) {
    $obj = new owners($data, $search);
    return object($obj);
  }
  
  function period($data = NULL, $search = NULL) {
    $obj = new period($data, $search);
    return object($obj);
  }

  function periods($data = NULL, $search = NULL) {
    $obj = new periods($data, $search);
    return object($obj);
  }

  function person($data = NULL, $search = NULL) {
    $obj = new person($data, $search);
    return object($obj);
  }

  function persons($data = NULL, $search = NULL) {
    $obj = new persons($data, $search);
    return object($obj);
  }

  function player($data = NULL, $search = NULL) {
    $obj = new player($data, $search);
    return object($obj);
  }

  function players($data = NULL, $search = NULL) {
    $obj = new players($data, $search);
    return object($obj);
  }

  function qualGroup($data = NULL, $search = NULL) {
    $obj = new qualGroup($data, $search);
    return object($obj);
  }

  function qualGroups($data = NULL, $search = NULL) {
    $obj = new qualGroups($data, $search);
    return object($obj);
  }

  function region($data = NULL, $search = NULL) {
    $obj = new region($data, $search);
    return object($obj);
  }

  function regions($data = NULL, $search = NULL) {
    $obj = new regions($data, $search);
    return object($obj);
  }

  function score($data = NULL, $search = NULL) {
    $obj = new score($data, $search);
    return object($obj);
  }

  function scores($data = NULL, $search = NULL) {
    $obj = new scores($data, $search);
    return object($obj);
  }

  function set($data = NULL, $search = NULL) {
    $obj = new set($data, $search);
    return object($obj);
  }

  function sets($data = NULL, $search = NULL) {
    $obj = new sets($data, $search);
    return object($obj);
  }

  function task($data = NULL, $search = NULL) {
    $obj = new task($data, $search);
    return object($obj);
  }

  function tasks($data = NULL, $search = NULL) {
    $obj = new tasks($data, $search);
    return object($obj);
  }

  function team($data = NULL, $search = NULL) {
    $obj = new team($data, $search);
    return object($obj);
  }

  function teams($data = NULL, $search = NULL) {
    $obj = new teams($data, $search);
    return object($obj);
  }

  function tournament($data = NULL, $search = NULL) {
    $obj = new tournament($data, $search);
    return object($obj);
  }

  function tournaments($data = NULL, $prop = NULL) {
    $obj = new tournaments($data, $prop);
    return object($obj);
  }

  function tshirt($data = NULL, $search = NULL) {
    $obj = new tshirt($data, $search);
    return object($obj);
  }

  function tshirts($data = NULL, $search = NULL) {
    $obj = new tshirts($data, $search);
    return object($obj);
  }

  function volunteer($data = NULL, $search = NULL) {
    $obj = new volunteer($data, $search);
    return object($obj);
  }

  function volunteers($data = NULL, $search = NULL) {
    $obj = new volunteers($data, $search);
    return object($obj);
  }

?>