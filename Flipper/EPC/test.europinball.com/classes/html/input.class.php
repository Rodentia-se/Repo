<?php

  class input extends html {
    
    public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
      if ($name) {
        $params['name'] = $name;
        $this->params['name'] = $name;
      }
      $params['id'] = ($params['id']) ? $params['id'] : $params['name'];
      if ($type && !($this instanceof select)) {
        $params['type'] = $type;
      }
      $this->label($label);
      $params['data-previous'] = ($params['previous']) ? $params['previous'] : (($params['data-previous']) ? $params['data-previous'] : $value);
      $this->settings['insideLabel'] = ($this instanceof check) ? TRUE : FALSE;
      $this->settings['insideLabel'] = FALSE;
      $this->settings['beforeLabel'] = ($this instanceof check) ? TRUE : FALSE;
      if ($this instanceof select) {
        $this->selfClose = FALSE;
      } else {
        $this->selfClose = TRUE;
        $this->contentParam = 'value';
      }
      if ($this instanceof checkbox) {
        unset($this->contentParam);
      }
      if (!$this->inline) {
        $this->inlineBlock = TRUE;
      }
      parent::__construct((($type == 'select') ? 'select' : 'input'), $value, $params, $name);
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
        case 'label':
          return $this->accessories['$prop'];
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
            if (!$this->accessories['label']) {
              $this->accessories['label'] = $this->label(TRUE);
            }
            $this->accessories['label']->addContent($this, $this, $this->settings['beforeLabel']);
          } else {
            $this->accessories['label']->delContent($this);
          }
        break;
        case 'beforeLabel':
          $this->settings['beforeLabel'] = ($value);
          if ($this->settings['insideLabel']) {
            $this->accessories['label']->addContent($this, $this, ($value));
          }
        break;
        case 'previous':
          $this->params['data-previous'] = $value;
        break;
        case 'label':
          return isset($this->accessories[$prop]);
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
        case 'label':
          return ($this->$prop) ? TRUE : FALSE;
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
        case 'label':
          $this->$prop(FALSE);
        break;
        default:
          parent::__unset($prop);
        break;
      }
    }

    protected function label($label) {
      if (!isset($label)) {
        debug('NOTSET');
        return $this->accessories['label'];
      } else if (is($label)) {
        debug('IS');
        if ($label === TRUE) {
        debug('TRUE');
          $this->accessories['label'] = new label(ucfirst($this->params['name']), $this->params['name'], $this->params['name'].'Label');
        } else {
        debug('!TRUE');
          $this->accessories['label'] = (isHtml($label)) ? $label : new label($label, $this->params['name']);
        }
        return isHtml($this->accessories['label']);
      } else {
        $this->accessories['label'] = NULL;
        return TRUE;
      }
    }
    
    public function getHtml($label = TRUE, $input = TRUE) {
      if ($input) {
        if ($label && $this->accessories['label']) {
          if ($this->insideLabel) {
            $this->accessories['label']->addContent($this, $this, $this->beforeLabel);
            return $this->accessories['label']->getHtml();
          } else {
            if ($this->beforeLabel) {
              return parent::getHtml().$this->accessories['label']->getHtml();
            } else {
              return $this->accessories['label']->getHtml().parent::getHtml();
            }
          }
        } else {
          return parent::getHtml();
        }
      } else {
        return ($label && $this->accessories['label']) ? $this->accessories['label']->getHtml() : NULL;
      }
    }
    
  }
  
?>