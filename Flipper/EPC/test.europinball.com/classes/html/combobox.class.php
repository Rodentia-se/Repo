<?php

  class combobox extends jqueryui {
    
    public function __construct($selector = NULL, $indents = 0) {
      $this->settings['jsReq'][] = 'jquery.combobox';
      $this->settings['cssReq'][] = 'jquery.combobox';
      parent::__construct($selector, 'combobox', 'command', NULL, NULL, $settings, $indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>
