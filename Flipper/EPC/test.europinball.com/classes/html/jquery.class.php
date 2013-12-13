<?php

  class jquery extends scriptCode {
    
    public function __construct($code = NULL, array $params = NULL, $indents = 0) {
      parent::__construct($code, $params, $indents);
      $this->settings['onReady'] = TRUE;
    }
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    protected function getContent($index = NULL, $string = TRUE) {
      return (($this->settings['onReady']) ? "$(document).ready(function() {\n" : '').parent::getContent($index, $string).(($this->settings['onReady']) ? "\n});" : '');
    }


  }
  
?>