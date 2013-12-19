<?php

  class loading extends dialog {
    
    public function __construct($selector = NULL, $appendTo = NULL, array $props = NULL, $indents = 0) {
      $props['dialogClass'] = ($props['dialogClass']) ? $props['dialogClass'] : "noTitleBar transparent";
      $props['resizable'] = ($props['resizable']) ? $props['resizable'] : FALSE;
      $props['appendTo'] = ($appendTo) ? $appendTo : $props['appendTo'];
      parent::__construct($selector, $props, $indents);
    }
//    dialog public function __construct($selector = NULL, array $props = NULL, $indents = 0) {
//    jqueryui public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    jquery public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>
