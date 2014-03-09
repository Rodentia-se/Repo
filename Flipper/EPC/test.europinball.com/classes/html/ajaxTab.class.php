<?php

  class ajaxTab extends link {
    
    public function __construct($url = NULL, $text = 'link', array $params = NULL) {
      $params['data-title'] = ($params['data-title']) ? $params['data-title'] : $text;
      parent::__construct($url, $text, $params);
      debug($this);
    }
//    public function __construct($url = NULL, $text = 'link', array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>