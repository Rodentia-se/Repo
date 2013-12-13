<?php

  class input extends html {
    
    protected $label;

    public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
      if ($name) {
        $params['name'] = $name;
      }
      if ($type) {
        $params['type'] = $type;
      }
      if ($label === TRUE) {
        $this->label = new label(ucfirst($name), $name, $name.'Label');
      } else if (is($label)) {
        $this->label = (isHtml($label)) ? $label : new label($label, $name);
      }
      $params['data-previous'] = ($params['previous']) ? $params['previous'] : (($params['data-previous']) ? $params['data-previous'] : $value);
      parent::__construct('input', $value, $params, $name, $class, $css);
      $this->settings = mergeToArray(parent::$settings, array('insideLabel' => FALSE, 'beforeLabel' => FALSE));
      $this->selfClose = true;
      $this->block = true;
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    public function __get($prop) {
      switch($prop) {
        case 'insideLabel':
        case 'beforeLabel':
          return $this->settings[$prop];
        break;
        case 'previous':
          return $this->params['data-previous'];
        break;
        default:
          return parent::__get($prop);
        break;
      }
    }

    public function __set($prop, $value) {
      switch($prop) {
        case 'insideLabel':
          $this->settings['insideLabel'] = ($value);
          if ($value) {
            if ($this->label) {
              $this->label = new label(ucfirst($name), $name.'Label');
            }
            $this->label->addContent($this, $this, $this->settings['beforeLabel']);
            }
          } else {
            $this->label->delContent($this);
          }
        break;
        case 'beforeLabel':
          $this->settings['beforeLabel'] = ($value);
          if ($this->settings['insideLabel']) {
            $this->label->addContent($this, $this, ($value));
          }
        break;
        case 'previous':
          $this->params['data-previous'] = $value;
        break;
        default:
          parent::__set($prop, $value);
        break;
      }
    }
    
    public function __isset($prop) {
      switch($prop) {
        case 'insideLabel':
        case 'beforeLabel':
          return isset($this->settings[$prop]);
        break;
        case 'previous':
          return isset($this->params['data-previous']);
        break;
        default:
          return parent::__isset($prop);
        break;
      }
    }

    public function __unset($prop) {
      switch($prop) {
        case 'insideLabel':
        case 'beforeLabel':
          $this->__set($prop, FALSE);
        break;
        case 'previous':
          unset($this->params['data-previous']);
        break;
        default:
          parent::__unset($prop);
        break;
      }
    }

    public function $this->getHtml($label = TRUE, $input = TRUE) {
      if ($input) {
        if ($label && $this->label) {
          if ($this->insideLabel) {
            $this->label->addContent($this, $this, $this->beforeLabel);
            return $this->label->getHtml();
          } else {
            if ($this->beforeLabel) {
              return parent::getHtml().$this->label->getHtml();
            } else {
              return $this->label->getHtml().parent::getHtml();
            }
          }
        } else {
          return parent::getHtml();
        }
      } else {
        return ($label && $this->label) ? $this->label->getHtml() : NULL;
      }
    }
    
  }
  
?>