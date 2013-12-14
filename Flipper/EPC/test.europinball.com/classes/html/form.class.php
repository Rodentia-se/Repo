<?php

  class form extends html {
    
    public function __construct($id = NULL, $action = NULL, $method = 'POST', array $params = NULL) {
      if ($action) {
        $params['action'] = $action;
      }
      if ($method) {
        $params['method'] = $method; 
      }
      parent::__construct('form', NULL, $params, $id);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>