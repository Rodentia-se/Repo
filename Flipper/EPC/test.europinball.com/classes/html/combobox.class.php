<?php

  class combobox extends jquery {
    
    public function __construct($selector = NULL, array $settings = NULL, $indents = 0) {
      $settings['required'] = array('jquery.js', 'jquery-ui.js', 'jquery.combobox.js');
      parent::__construct($selector, 'combobox', 'command', NULL, NULL, $indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, array $settings = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>
