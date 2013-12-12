<?php

  class span extends html {
    
    public function __construct($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $this->inline = true;
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      parent::__construct('div', $content, $params, $id, $class, $css);
    }
    
  }
  
?>