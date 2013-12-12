<?php

  class form extends html {
    
    public function __construct($id = NULL, $action = NULL, $method = 'POST', array $params = NULL) {
      $this->block = true;
      if ($action) {
        $params['action'] = $action;
      }
      if ($method) {
        $params['action'] = $action;
      }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      parent::__construct('div', $content, $params, $id, $class, $css);
    }
    
  }
  
?>