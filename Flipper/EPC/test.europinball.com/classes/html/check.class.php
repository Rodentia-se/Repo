<?php

  class check extends input {
    
    public function __construct($name = NULL, $value = NULL, $type = 'checkbox', $checked = FALSE, array $params = NULL) {
      $params['name'] = ($name) ? $name : (($params['name']) ? $params['name'] : $value);
      $params['id'] = ($params['id']) ? $params['id'] : $name;
      if ($checked || $params['checked']) {
        $params['checked'] = 'checked';
      } else {
        unset($params['checked']);
      }
      parent::__construct($name, $value, $type, TRUE, $params);
    }
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    public function __get($prop) {
      switch($prop) {
        case 'checked':
          return ($this->params['checked'] == 'checked') ? TRUE : FALSE;
        break;
        default:
          return parent::__get($prop);
        break;
      }
    }

    public function __set($prop, $value) {
      switch($prop) {
        case 'checked':
          if ($value) {
            $this->params['checked'] = 'checked';
          } else {
            unset($this->params['checked']);
          }
        break;
        default:
          parent::__set($prop, $value);
        break;
      }
    }
    
    public function __isset($prop) {
      switch($prop) {
        case 'checked':
          return ($this->params['checked'] == 'checked') ? TRUE : FALSE;
        break;
        default:
          return parent::__isset($prop);
        break;
      }
    }

    public function __unset($prop) {
      switch($prop) {
        case 'checked':
          unset($this->params['checked']);
        break;
        default:
          parent::__unset($prop);
        break;
      }
    }

  }
  
?>