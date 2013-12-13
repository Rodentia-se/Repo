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
      parent::__construct($contents, $indents);
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
          $code .= '$('.$this->jquery['selector'].')';
          if (is_array($this->jquery['command']) && count($this->jquery['command']) > 0) {
            foreach ($this->jquery['command'] as $key => $command) {
              $code = '.'.$this->jquery['object'].'("'.$command.'"'.(($command) ? ', "'.$this->contents[$key].'")' : '');
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
      switch($prop) {
        case 'selector':
        case 'object':
        case 'function':
        case 'command':
          return $this->jquery[$prop];
        break;
        default:
          return parent::__get($prop);
        break;
      }
    }

    public function __set($prop, $value) {
      switch($prop) {
        case 'selector':
        case 'object':
        case 'function':
        case 'command':
          $this->jquery[$prop] = $value;
        break;
        default:
          parent::__set($prop, $value);
        break;
      }
    }
    
    public function __isset($prop) {
      switch($prop) {
        case 'selector':
        case 'object':
        case 'function':
        case 'command':
          return isset($this->jquery[$prop]);
        break;
        default:
          return parent::__isset($prop);
        break;
      }
    }

    public function __unset($prop) {
        case 'selector':
        case 'object':
        case 'function':
        case 'command':
          unset($this->jquery[$prop]);
        break;
        default:
          parent::__unset($prop);
        break;
      }
    }

  }
  
?>