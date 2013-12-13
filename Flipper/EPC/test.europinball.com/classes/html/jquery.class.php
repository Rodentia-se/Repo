<?php

  class jquery extends scriptCode {
    
    public function __construct($code = NULL, array $params = NULL) {
      parent::__construct($code, $params);
      $this->settings['onReady'] = TRUE;
    }
//    public function __construct($source = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    protected function getContent($index = NULL, $string = TRUE) {
      return (($this->settings['onReady']) ? '$(document).ready(function() {\n' : '').parent::get('contents', $index, $string).(($this->settings['onReady']) ? '});' : '');
    }


  }
  
?>