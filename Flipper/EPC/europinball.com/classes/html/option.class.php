<?php

  class option extends html {
    
    public function __construct($text = NULL, $value = NULL, $selected = FALSE, array $params = NULL) {
      $params['id'] = ($params['id']) ? $params['id'] : $params['name'];
      $params['value'] = $value;
      $params['selected'] = $selected;
      $this->inlineBlock = TRUE;
      parent::__construct('option', $text, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>