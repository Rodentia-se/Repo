<?php

  class tX extends html {
    
    public function __construct($type = 'td', $contents = NULL, array $params = NULL) {
      $this->inlineBlock = TRUE;
      parent::__construct($type, $contents, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>