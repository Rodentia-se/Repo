<?php

  class span extends html {
    
    public function __construct($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $this->inline = TRUE;
      parent::__construct('span', $contents, $params, $id, $class, $css);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>