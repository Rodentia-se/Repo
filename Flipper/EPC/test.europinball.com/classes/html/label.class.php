<?php

  class label extends html {
    
    public function __construct($contents = NULL, $for = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      if (is($for)) {
        $params['for'] = $for;
      } 
      $class = (!is($class) && $class !== FALSE) ? 'label' : $class;
      $id = (is($id)) ? $id : (($for) ? $for.'Label' : static::newId(NULL, 'Label');
      parent::__construct('label', $contents, $params, $id, $class, $css);
      $this->inline = true;
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>