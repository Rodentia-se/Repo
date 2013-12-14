<?php

  class cssFile extends html {
    
    public function __construct($file = NULL, array $params = NULL) {
      $params['type'] = ($params['type']) ? $params['type'] : 'text/css';
      $params['rel'] = ($params['rel']) ? $params['rel'] : 'stylesheet';
      $file = ($file) ? $file : $params['href'];
      $this->contentParam = 'href';
      $this->inlineBlock = TRUE;
      $this->selfClose = TRUE;
      parent::__construct('link', $file, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>