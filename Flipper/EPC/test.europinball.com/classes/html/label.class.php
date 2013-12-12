<?php

  class label extends html {
    
    public function __construct($contents = NULL, $for = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $this->inline = true;
      if (is($for)) {
        $params['for'] = $for;
      } 
      if (!is($class) && $class !== FALSE) {
        $this->addClasses('label');
      }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      parent::__construct('label', $content, $params, $id, $class, $css);
      if (!is($this->id)) {
        $this->id = $this->for.'Label';
      }
    }
    
  }
  
?>