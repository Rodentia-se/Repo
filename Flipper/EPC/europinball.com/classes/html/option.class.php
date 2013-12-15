<?php

  class option extends html {
    
    public function __construct($text = NULL, $value = NULL, $selected = FALSE, array $params = NULL) {
      if (isObj($text)) {
        $params['value'] = $text->id;
        $text = $text->name;
      } else {
        $params['value'] = $value;
      }
      if(is_object($text)) {
        debug("OBJ", "OBJ", TRUE);
      }
      $params['id'] = ($params['id']) ? $params['id'] : $params['name'];
      $params['selected'] = $selected;
      $this->inlineBlock = TRUE;
      parent::__construct('option', $text, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>