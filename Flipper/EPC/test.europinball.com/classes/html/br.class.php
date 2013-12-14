<?php

  class br extends html {
    
    public function __construct($id = NULL, $class = NULL, array $params = NULL) {
      $this->selfClose = TRUE;
      parent::__construct('br', NULL, $params, $id, $class);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>