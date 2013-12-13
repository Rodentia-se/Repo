<?php

  class tooltip extends jquery {
    
    public function __construct($selector = NULL, $contents = NULL, $new = TRUE, $indents = 0) {
      $object = 'tooltipster';
      if ($new) {
      $function = TRUE;
      $settings = array(
          'theme' => '.tooltipster-light',
          'trigger' => 'custom',
          'position' => 'right',
          'timer' => 3000
        );
      } else {
        $function = FALSE;
        $command = array('update', 'show');
        $contents = array($contents, FALSE);
      }
      parent::__construct($selector, $this->jquery['tooltipster'], $this->jquery['function'], $this->jquery['command'], $contents, $settings, $indents);
    }
//    jquery public function __construct($selector = NULL, $object = NULL, $function = NULL, $comamnd = NULL, $contents = NULL, array $settings, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    protected function getContent($index = NULL, $string = TRUE) {
      if (is($index)) {
        return parent::getContent($index, $string);
      } else {
        if ($this->jquery['function']) {
          foreach ($this->jquery->settings as $option => $value) {
            if (array_key_exists($option, $this->jquery)) {
              $code .= $option.': '.$value.",\n";
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