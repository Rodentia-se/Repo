<?php

  class radio extends check {
    
    public function __construct($name = NULL, $value = NULL, $checked = FALSE, array $params = NULL) {
      $params['name'] = ($name) ? $name : (($params['name']) ? $params['name'] : $value);
      $params['id'] = ($params['id']) ? $params['id'] : $name;
      parent::__construct($name, $value, 'radio', $checked, $params);
    }
//    check public function __construct($name = NULL, $value = NULL, $type = 'checkbox', array $params = NULL) {
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>