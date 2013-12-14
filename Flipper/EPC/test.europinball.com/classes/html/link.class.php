<?php

  class link extends html {
    
    public function __construct($url = NULL, $text = 'link', array $params = NULL) {
      $params['href'] = ($url) ? $url : $params['href'];
      $this->inline = TRUE;
      parent::__construct('a', $text, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>