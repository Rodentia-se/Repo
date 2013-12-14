<?php

  class hidden extends input {
    
    public function __construct($name = NULL, $value = 'yes', array $params = NULL) {
      parent::__construct($name, $value, 'hidden', FALSE, $params);
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    }
    
  }
  
?>