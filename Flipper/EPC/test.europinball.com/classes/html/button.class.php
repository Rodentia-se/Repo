<?php

  class button extends input {
    
    public function __construct($value = 'submit', $name = NULL, array $params = NULL) {
      $this->block = true;
      $params['name'] = ($name) ? $name : $value;
      parent::__construct($name, $value, 'button', FALSE, $params);
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    }
    
  }
  
?>