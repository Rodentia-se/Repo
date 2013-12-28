<?php

  class datatables extends jquery {
    
    public function __construct($selector = NULL, array $props = NULL, $indents = 0) {
        $type = 'object';
        $props = array_merge(array(
          'bProcessing' => TRUE,
          'bJQueryUI' => TRUE,
          'sPaginationType' => 'full_numbers',
          'oLanguage' => '{"sProcessing": "<img src=\"'.config::$baseHref.'/images/ajax-loader-white.gif\" alt=\"Loading data...\">"}',
          'iDisplayLength' => -1,
          'aLengthMenu' => '[[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]'
        ), (array) $props);
      parent::__construct($selector, 'dataTable', 'object', NULL, $props, $indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>