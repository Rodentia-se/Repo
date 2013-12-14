<?php

  class hX extends html {
    
    public function __construct($hLevel = 1, $contents = NULL, array $params = NULL) {
      $this->settings['hLevel'] = ($hLevel && is_int($hLevel) && $hLevel < 5) ? $hLevel : 1;
      $this->inline = TRUE;
      parent::__construct('h'.$this->settings['hLevel'], $contents, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>