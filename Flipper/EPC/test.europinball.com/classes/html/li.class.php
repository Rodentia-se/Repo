<?php

  class li extends html {
    
    public function __construct($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $this->inlineBlock = TRUE;
      parent::__construct('li', $contents, $params, $id, $class, $css);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>