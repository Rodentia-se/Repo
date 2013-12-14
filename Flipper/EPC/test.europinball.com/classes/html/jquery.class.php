<?php

  class jquery extends scriptCode {
    
    protected $jquery = array();
    
    public function __construct($selector = NULL, $tool = NULL, $type = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
      $this->jquery = array(
        'selector' => $selector,
        'tool' => $tool,
        'type' => $type,
        'props' => $props
      );
      switch ($type) {
        case 'command':
          if (is_array($contents)) {
            foreach ($contents as $command => $param) {
              $this->jquery['command'][] = $command;
              $this->contents[] = $param;
            }
          } else {
            $this->jquery['command'][] = $contents;
            $this->contents[] = FALSE;
          }
        break;
        case 'object':
          $this->jquery['contentProp'] = $props['contentProp'];
          unset($this->jquery['props']['contentProp']);
          $this->contents = (is_array($contents)) ? $contents : array($contents);
        break;
        case 'code':
        case 'function':
          $this->contents = (is_array($contents)) ? $contents : array($contents);
        break;
      }
      parent::__construct(NULL, NULL, $indents);
      $this->settings['onReady'] = TRUE;
      debug($this);
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
        $code = '$("'.$this->jquery['selector'].'")';
        if ($this->jquery['type'] == 'function') {
          $code .= '.'.$this->jquery['tool']."(function() {\n".$contents."\n});";
        } else if ($this->jquery['type'] == 'object') {
          $code .= '.'.$this->jquery['tool']."({\n";
          if ($this->jquery['props']) {
            foreach ($this->jquery['props'] as $prop => $val) {
              if (is_string($val) && substr($val, 0, 8) != 'function') {
                $delimiter = '"';
              } else {
                $delimiter = '';
              }
              $code.= $prop.': '.$delimiter.$val.$delimiter.",\n";
            }
            if ($this->jquery['contentProp'] && count($this->contents) > 0) {
              foreach ($this->contents as $key => $content) {
                $contents .= parent::getContent($key, $string);
              }
            }
            $code .= $this->jquery['contentProp'].': "'.$contents;
          }
          $code = rtrim($code, ',')."\n});\n";
        } else if ($this->jquery['type'] == 'command') {
          if (is_array($this->jquery['command']) && count($this->jquery['command']) > 0) {
            foreach ($this->jquery['command'] as $key => $command) {
              $code .= '.'.$this->jquery['tool'].'("'.$command.'"'.(($command) ? ', "'.$this->contents[$key].'")' : '');
            }
          } else { 
            $code .= '.'.$this->jquery['tool'].'("'.$this->jquery['command'].'", "'.$this->contents[0].'")';
          }
          $code .= ';';
        } else {
          $code = parent::getContent($index, $string);
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