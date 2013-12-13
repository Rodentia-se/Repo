<?php

  class tooltip extends jquery {
    
    public $tooltipSettings = array(
      'theme',
      'trigger',
      'position',
      'offsetx',
      'offsety',
      'timer'
    );

    public function __construct($selector = NULL, $contents = NULL, $new = TRUE, $indents = 0) {
      if ($new) {
        $this->jquery = array(
          'selector' => $selector,
          'object' => 'tooltipster'
          'function' => TRUE,
          'theme' => '.tooltipster-light',
          'trigger' => 'custom',
          'position' => 'right',
          'timer' => 3000
        );
      } else {
        $this->jquery = array(
          'selector' => $selector,
          'object' => 'tooltipster'
          'function' => TRUE,
          'command' => array('update', 'show'
        );
        $contents = array($contents, FALSE);
      }
      parent::__construct($selector, $settings['object'], $settings['function'], $settings['command'], $contents, $indents);
    }
//    jquery public function __construct($selector = NULL, $object = NULL, $function = NULL, $comamnd = NULL, $contents = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    protected function getContent($index = NULL, $string = TRUE) {
      if (is($index)) {
        return parent::getContent($index, $string);
      } else {
        if ($this->jquery['function']) {
          foreach ($this->tooltipSettings as $option) {
            if (array_key_exists($option, $this->jquery)) {
              $code .= $option.': '.$this->jquery[$option].",\n";
            }
          }
          $code .= 'content: "'.parent::getContent($index, $string).'"';
          return $code."\n";
        } else {
          return parent::getContent($index, $string);
        }
      }
    }

  }
  
?>