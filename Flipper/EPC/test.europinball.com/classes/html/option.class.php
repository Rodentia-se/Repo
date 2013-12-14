<?php

  class option extends html {
    
    public function __construct($text = NULL, $value = NULL, $selected = FALSE, array $params = NULL) {
      $params['id'] = ($params['id']) ? $params['id'] : $params['name'];
      $params['value'] = $value;
      if ($selected) {
        $params['selected'] = 'selected';
      }
      $params['data-previous'] = ($params['previous']) ? $params['previous'] : (($params['data-previous']) ? $params['data-previous'] : $value);
      unset($this->contentCrlf);
      parent::__construct('option', $text, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    public function __get($prop) {
      switch($prop) {
        case 'selected':
          return ($this->params['selected'] == 'selected') ? TRUE : FALSE;
        break;
        default:
          return parent::__get($prop);
        break;
      }
    }

    public function __set($prop, $value) {
      switch($prop) {
        case 'selected':
          if ($value) {
            $this->params['selected'] = 'selected';
          } else {
            unset($this->params['selected']);
          }
        break;
        default:
          parent::__set($prop, $value);
        break;
      }
    }
    
    public function __isset($prop) {
      switch($prop) {
        case 'selected':
          return ($this->params['selected'] == 'selected') ? TRUE : FALSE;
        break;
        default:
          return parent::__isset($prop);
        break;
      }
    }

    public function __unset($prop) {
      switch($prop) {
        case 'selected':
          unset($this->params['selected']);
        break;
        default:
          parent::__unset($prop);
        break;
      }
    }

  }
  
?>