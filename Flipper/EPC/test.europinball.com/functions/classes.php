<?php

  spl_autoload_register(function($class) {
    if (is_file(config::$baseDir.'/classes/'.$class.'.class.php')) {
      include config::$baseDir.'/classes/'.$class.'.class.php';
    }
  });
  
  function division($data = NULL, $search = NULL) {
    return new division($data, $search);
  }

  function tournament($data = NULL, $search = NULL) {
    return new tournament($data, $search);
  }

  function location($data = NULL, $search = NULL) {
    return new location($data, $search);
  }

  function entry($data = NULL, $search = NULL) {
    return new entry($data, $search);
  }

  function score($data = NULL, $search = NULL) {
    return new score($data, $search);
  }

  function match($data = NULL, $search = NULL) {
    return new match($data, $search);
  }

  function set($data = NULL, $search = NULL) {
    return new set($data, $search);
  }

  function game($data = NULL, $search = NULL) {
    return new game($data, $search);
  }

  function machine($data = NULL, $search = NULL) {
    return new machine($data, $search);
  }

  function period($data = NULL, $search = NULL) {
    return new period($data, $search);
  }

  function task($data = NULL, $search = NULL) {
    return new task($data, $search);
  }

  function qualGroup($data = NULL, $search = NULL) {
    return new qualGroup($data, $search);
  }

  function city($data = NULL, $search = NULL) {
    return new city($data, $search);
  }

  function region($data = NULL, $search = NULL) {
    return new region($data, $search);
  }

  function country($data = NULL, $search = NULL) {
    return new country($data, $search);
  }

  function continent($data = NULL, $search = NULL) {
    return new continent($data, $search);
  }

  function gender($data = NULL, $search = NULL) {
    return new gender($data, $search);
  }

  function manufacturer($data = NULL, $search = NULL) {
    return new manufacturer($data, $search);
  }

  function team($data = NULL, $search = NULL) {
    return new team($data, $search);
  }

  function player($data = NULL, $search = NULL) {
    return new player($data, $search);
  }

  function person($data = NULL, $search = NULL) {
    return new person($data, $search);
  }

  function owner($data = NULL, $search = NULL) {
    return new owner($data, $search);
  }
  
  function divisions($data = NULL, $prop = NULL) {
    return new divisions($data, $prop);
  }

  function tournaments($data = NULL, $prop = NULL) {
    return new tournaments($data, $prop);
  }

  function locations($data = NULL, $prop = NULL) {
    return new locations($data, $prop);
  }

  function entries($data = NULL, $prop = NULL) {
    return new entries($data, $prop);
  }

  function scores($data = NULL, $prop = NULL) {
    return new scores($data, $prop);
  }

  function matches($data = NULL, $prop = NULL) {
    return new matches($data, $prop);
  }

  function sets($data = NULL, $prop = NULL) {
    return new sets($data, $prop);
  }

  function games($data = NULL, $prop = NULL) {
    return new games($data, $prop);
  }

  function machines($data = NULL, $prop = NULL) {
    return new machines($data, $prop);
  }

  function periods($data = NULL, $prop = NULL) {
    return new periods($data, $prop);
  }

  function tasks($data = NULL, $prop = NULL) {
    return new tasks($data, $prop);
  }

  function qualGroups($data = NULL, $prop = NULL) {
    return new qualGroups($data, $prop);
  }

  function cities($data = NULL, $prop = NULL) {
    return new cities($data, $prop);
  }

  function regions($data = NULL, $prop = NULL) {
    return new regions($data, $prop);
  }

  function countries($data = NULL, $prop = NULL) {
    return new countries($data, $prop);
  }

  function continents($data = NULL, $prop = NULL) {
    return new continents($data, $prop);
  }

  function genders($data = NULL, $prop = NULL) {
    return new genders($data, $prop);
  }

  function manufacturers($data = NULL, $prop = NULL) {
    return new manufacturers($data, $prop);
  }

  function teams($data = NULL, $prop = NULL) {
    return new teams($data, $prop);
  }

  function players($data = NULL, $prop = NULL) {
    return new players($data, $prop);
  }

  function persons($data = NULL, $prop = NULL) {
    return new persons($data, $prop);
  }

  function owners($data = NULL, $prop = NULL) {
    return new owners($data, $prop);
  }

?>