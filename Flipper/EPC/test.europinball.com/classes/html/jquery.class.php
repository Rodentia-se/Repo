<?php

  class jquery extends scriptCode {
    
    public $jquery = array();
    
    public function __construct($selector = NULL, $object = NULL, $function = NULL, $comamnd = NULL, $contents = NULL, $indents = 0) {
      $this->jquery = array(
        'selector' => $selector,
        'object' => $object,
        'function' => $function,
        'command' => $command
      );
      $this->contents = (is_array($contents)) ? $contents : array($comamnd);
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
          $code .= '$('.$this->jquery['selector'].').'.$this->jquery['object']."(function() {\n".$contents."\n});";
        } else {
          $code = '$('.$this->jquery['selector'].')';
          if (is_array($this->jquery['command']) && count($this->jquery['command']) > 0) {
            foreach ($this->jquery['command'] as $key => $command) {
              $code .= '.'.$this->jquery['object'].'("'.$command.'"'.(($command) ? ', "'.$this->contents[$key].'")' : '');
            }
          } else {
            $code = '.'.$this->jquery['object'].'("'.$this->jquery['command'].'", "'.$this->contents[0].'")';
          }
          $code .= ';';
        }
        return ltrim($jsbeautifier->beautify((($this->settings['onReady']) ? static::$indenter."$(document).ready(function() {\n" : '').$code.(($this->settings['onReady']) ? "\n});" : ''), $options));
      }
    }

    public function __get($prop) {
      if (array_key_exists($prop, $this->jquery)) {
        return $this->jquery[$prop];
      } else {
        return parent::__get($prop);
      }
    }

    public function __set($prop, $value) {
      if (array_key_exists($prop, $this->jquery)) {
        $this->jquery[$prop] = $value;
      } else {
        parent::__set($prop, $value);
      }
    }
    
    public function __isset($prop) {
      if (array_key_exists($prop, $this->jquery)) {
        return isset($this->jquery[$prop]);
      } else {
        return parent::__isset($prop);
      }
    }

    public function __unset($prop) {
      if (array_key_exists($prop, $this->jquery)) {
        unset($this->jquery[$prop]);
      } else {
        parent::__unset($prop);
      }
    }

  }
  
?>