<?php

  class jquery extends scriptCode {
    
    public function __construct($code = NULL, array $params = NULL, $indents = 0) {
      parent::__construct($code, $params, $indents);
      $this->settings['onReady'] = TRUE;
    }
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    protected function getContent($index = NULL, $string = TRUE) {
      if (is($index)) {
        return parent::getContent($index, $string);
      } else {
        $options = new BeautifierOptions();
        $options->indent_size = strlen(static::$indenter);
        $options->indent_char = substr(static::$indenter, 0, 1);
        $options->indent_level = static::$indents + 1;
        $options->max_preserve_newlines = 1;
        $jsbeautifier = new JSBeautifier();
        return static::$indenter.ltrim($jsbeautifier->beautify((($this->settings['onReady']) ? static::$indenter."$(document).ready(function() {\n" : '').parent::getContent($index, $string).(($this->settings['onReady']) ? "\n});" : ''), $options));
      }
    }


  }
  
?>