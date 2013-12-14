<?php

  class hr extends html {
    
    public function __construct($id = NULL, $class = NULL, array $params = NULL) {
      parent::__construct('hr', NULL, $params, $id, $class);
      $this->selfClose = TRUE;
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>