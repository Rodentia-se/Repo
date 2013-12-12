<?php

  namespace html;
  
  class div extends html {
    
    public $element = 'div';
    
    public function __construct($contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      static::$indents = $indents;
      if (is($id)) {
        $params['id'] = $id;
      }
      $this->addParams($params);
      debug($this->params);
      $this->addClasses($class);
      $this->addCss($css);
      $this->addContent($contents);
    }
?>