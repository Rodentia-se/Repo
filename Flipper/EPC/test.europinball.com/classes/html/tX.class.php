<?php

  class tX extends html {
    
    public function __construct($type = 'td', $contents = NULL, array $params = NULL) {
      parent::__construct($type, $contents, $params);
      $this->inline = TRUE;
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>