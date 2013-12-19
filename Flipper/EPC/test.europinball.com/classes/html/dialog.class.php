<?php

  class dialog extends jqueryui {
    
    public function __construct($selector = NULL, array $props = NULL, $indents = 0) {
      $props['autoOpen'] = ($props['autoOpen']) ? $props['autoOpen'] : FALSE;
      $props['modal'] = ($props['modal']) ? $props['modal'] : TRUE;
      $props['width'] = ($props['width']) ? $props['width'] : "auto";
      $props['height'] = ($props['height']) ? $props['height'] : "auto";
      $props['dialogClass'] = ($props['dialogClass']) ? $props['dialogClass'] : "noTitleBar transparent";
      $props['resizable'] = ($props['resizable']) ? $props['resizable'] : FALSE;
      $props['appendTo'] = ($props['appendTo']) ? $props['appendTo'] : $selector;
      parent::__construct($selector, 'dialog', 'object', NULL, $props, $indents);
    }
//    jqueryui public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    jquery public function __construct($selector = NULL, $tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>
