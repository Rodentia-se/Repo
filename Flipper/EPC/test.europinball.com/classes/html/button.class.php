<?php

  class button extends input {
    
    protected $form;
    
    public function __construct($value = 'submit', $name = NULL, $form = TRUE, array $params = NULL) {
      $params['name'] = ($name) ? $name : $value;
      if ($form === TRUE) {
        $this->form = new form($name.'Form');
      } else if (is($form)) {
        $this->form = (isHtml($form)) ? $form : new form($form);
      }
      parent::__construct($name, $value, 'button', FALSE, $params);
      $this->inline = true;
    }
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>