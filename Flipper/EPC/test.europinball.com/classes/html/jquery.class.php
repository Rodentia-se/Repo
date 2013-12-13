<?php

  class jquery extends scriptCode {
    
    public function __construct($code = NULL, array $params = NULL) {
      parent::__construct($code, $params) {
    }
//    public function __construct($source = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    public function $this->getHtml($onReady = TRUE) {
      $indents = (is($this->localIndents)) ? $this->localIndents : static::$indents;
      while ($i < $indents) {
        $indent .= static::$indenter;
        $i++;
      }
      return (($onReady) ? $this->crlf.$indent.'$(document).ready(function() {' : '').parent::getHtml().(($onReady) ? $this->crlf.$indent.'});' : '');
    }

  }
  
?>