<?php

  class scriptFile extends script {
    
    public function __construct($file = NULL, array $params = NULL) {
      $file = ($file) ? $file : $params['src'];
      $params['type'] = ($params['type']) ? $params['type'] : 'text/javascript';
      $this->contentParam = 'src';
      $this->settings['type'] == 'file';
      $this->inlineBlock = TRUE;
      parent::__construct($file, $params);
    }
//    public function __construct($source = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>