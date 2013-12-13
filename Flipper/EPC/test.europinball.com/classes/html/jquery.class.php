<?php

  class jquery extends scriptCode {
    
    public $jquery = array();
    
    public function __construct($selector = NULL, $object = NULL, $function = NULL, $comamnd = NULL, $contents = NULL, array $settings = NULL, $indents = 0) {
      $this->jquery = array(
        'selector' => $selector,
        'object' => $object,
        'function' => $function,
        'command' => $command, 
        'settings' => $settings
      );
      debug($this->jquery);
      $this->contents = (is_array($contents)) ? $contents : array($contents);
      parent::__construct(NULL, NULL, $indents);
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
        $options->indent_level = static::$indents;
        $options->max_preserve_newlines = 1;
        $jsbeautifier = new JSBeautifier();
        $contents = parent::getContent($index, $string);
        if ($this->jquery['function']) {
          $code = '$('.$this->jquery['selector'].').'.$this->jquery['object']."(function() {\n".$contents."\n});";
        } else {
          $code = '$('.$this->jquery['selector'].')';
          if (is_array($this->jquery['command']) && count($this->jquery['command']) > 0) {
            foreach ($this->jquery['command'] as $key => $command) {
              $code .= '.'.$this->jquery['object'].'("'.$command.'"'.(($command) ? ', "'.$this->contents[$key].'")' : '');
            }
          } else { 
            $code .= '.'.$this->jquery['object'].'("'.$this->jquery['command'].'", "'.$this->contents[0].'")';
          }
          $code .= ';';
        }
        return ltrim($jsbeautifier->beautify((($this->settings['onReady']) ? static::$indenter."$(document).ready(function() {\n" : '').$code.(($this->settings['onReady']) ? "\n});" : ''), $options));
      }
    }

  }
  
?>