<?php

  class tooltip extends jquery {
    
    public static $tooltipSettings = array(
      'theme',
      'trigger',
      'position',
      'offsetx',
      'offsety',
      'timer'
    );

    public function __construct($selector = NULL, $contents = NULL, $new = TRUE, $indents = 0) {
      $settings['selector'] = $selector;
      $settings['object'] = 'tooltipster';
      if ($new) {
        $settings['function'] = TRUE;
        $defaultSettings = array(
          'theme' => '.tooltipster-light',
          'trigger' => 'custom',
          'position' => 'right',
          'timer' => 3000
        );
        foreach ($defaultSettings as $setting => $value) {
          $settings[$setting] = ($settings[$ßetting]) ? $settings[$setting] : $defaultSettings[$ßetting];
        }
      } else {
        $settings['function'] = FALSE;
        $settings['command'] = array('update', 'show');
        $contents = array($contents, FALSE);
      }
      $this->jquery = mergeToArray($this->jquery, $settings);
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
          foreach ($this->$tooltipSettings as $option) {
            if (array_key_exists($this->jquery[$option])) {
              $code .= $option.': '.$this->jquery[$option].",\n";
            }
          }
          $code .= 'content: "'.parent::getContent($index, $string).'"';
        } else {
          return parent::getContent($index, $string);
        }
        return $code."\n";
      }
    }

  }
  
?>