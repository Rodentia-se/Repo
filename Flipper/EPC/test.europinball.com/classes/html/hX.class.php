<?php

  class hX extends html {
    
    protected $hLevel = 1;
    
    public function __construct($hLevel = 1, $contents = NULL, array $params = NULL) {
      $hLevel = ($hLevel && is_int($hLevel) && $hLevel < 5) ? $hLevel : 1;
      parent::__construct('h'.$hLevel, $contents, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>