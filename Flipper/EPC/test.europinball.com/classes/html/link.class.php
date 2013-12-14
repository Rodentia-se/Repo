<?php

  class link extends html {
    
    public function __construct($url = NULL, $contents = 'link', array $params = NULL) {
      $params['href'] = ($url) ? $url : $params['href'];
      parent::__construct('a', $contents, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>