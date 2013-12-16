<?php

  class jqueryui extends jquery {
    
    public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
      $this->settings['jsReq'][] = 'jquery-ui';
      $this->settings['cssReq'][] = 'jquery-ui';
      parent::__construct($selector, $tool, $jqtype, $contents, $props, $indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>