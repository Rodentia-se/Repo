<?php

  class button extends input {
    
    protected $script;
    
    public function __construct($value = 'submit', $name = NULL, array $params = NULL) {
      $params['name'] = ($name) ? $name : $value;
      $params['id'] = ($params['id']) ? $params['id'] : $name;
      parent::__construct($name, $value, 'button', FALSE, $params);
      $this->inline = true;
    }
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>