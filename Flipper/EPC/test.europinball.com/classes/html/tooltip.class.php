<?php

  class tooltip extends jquery {
    
    public function __construct($selector = NULL, $contents = NULL, $new = TRUE, $indents = 0) {
      if ($new) {
      $settings = array(
          'theme' => '.tooltipster-light',
          'trigger' => 'custom',
          'position' => 'right',
          'timer' => 3000
        );
      } else {
        $command = array('update', 'show');
        $contents = array($contents, FALSE);
      }
      parent::__construct($selector, 'tooltipster', $new, $command, $contents, $settings, $indents);
    }
//    jquery public function __construct($selector = NULL, $object = NULL, $function = NULL, $comamnd = NULL, $contents = NULL, array $settings, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?>