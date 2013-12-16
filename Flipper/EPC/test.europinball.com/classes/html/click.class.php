<?php

  class click extends jquery {
    
    public function __construct($selector = NULL, $code = NULL, array $settings = NULL, $indents = 0) {
      $settings['required'] = 'jquery.js';
      parent::__construct($selector, 'click', (($code) ? 'function' : 'command'), $code, NULL, $settings, static::$indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $type = NULL, $contents = NULL, array $props = NULL, array $settings = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, array €settings = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>