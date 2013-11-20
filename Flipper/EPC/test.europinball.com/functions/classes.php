<?php

  spl_autoload_register(function($class) {
    if (is_file(__ROOT__.'/classes/'.$class.'.class.php')) {
      include __ROOT__.'/classes/'.$class.'.class.php';
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

?>