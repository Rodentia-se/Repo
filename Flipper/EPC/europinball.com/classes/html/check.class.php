<?php

  class check extends input {
    
    public function __construct($name = NULL, $value = NULL, $type = 'checkbox', $checked = FALSE, array $params = NULL) {
      $params['name'] = ($name) ? $name : (($params['name']) ? $params['name'] : $value);
      $params['id'] = ($params['id']) ? $params['id'] : $name;
      if ($checked || $params['checked']) {
        $params['checked'] = 'checked';
      } else {
        unset($params['checked']);
      }
      parent::__construct($name, $value, $type, TRUE, $params);
    }
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>