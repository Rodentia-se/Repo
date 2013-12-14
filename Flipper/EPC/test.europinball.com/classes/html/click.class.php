<?php

  class tooltip extends jquery {
    
    public function __construct($selector = NULL, $code = NULL, $indents = 0) {
      parent::__construct($selector, 'click', (($code) ? 'function' : 'command'), $code, NULL, static::$indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $type = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>