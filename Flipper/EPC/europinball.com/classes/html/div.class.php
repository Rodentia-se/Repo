<?php

  class div extends html {
    
    public function __construct($id = NULL, $class = NULL, array $params = NULL) {
      parent::__construct('div', $content, $params, $id, $class, $css);
      $this->block = true;
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>