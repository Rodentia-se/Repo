<?php

  class th extends html {
    
    public function __construct($contents = NULL, array $params = NULL) {
      parent::__construct('th', trim($contents), $params);
    }
//    tX public function __construct($type = 'td', $contents = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>