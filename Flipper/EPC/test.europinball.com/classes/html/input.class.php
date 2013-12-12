<?php

  protected $label;
  protected $labelSettings = array('insideLabel' = FALSE, 'beforeLabel' = FALSE);

  class input extends html {
    
    public function __construct($name = NULL, $value = NULL, $type = 'text', $label = NULL, array $params = NULL) {
      $this->block = true;
      $this->selfClose = true;
      if ($name) {
        $params['name'] = $name;
      }
      if (is($label)) {
        $this->label = (isHtml($label)) ? $label : new label($label, $name);
      } else {
        if ($label !== FALSE) {
          $this->label = new label(ucfirst($name), $name.'Label');
        }
      }
      parent::__construct('input', $value, $params, $name, $class, $css);
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    }
    
    public function __get($prop) {
      switch($prop) {
        case 'insideLabel':
        case 'beforeLabel':
          return $this->labelSettings[$prop];
        break;
        default:
          return parent::__get($prop);
        break;
      }
    }

    public function __set($prop, $value) {
      switch($prop) {
        case 'insideLabel':
          $this->labelSettings['insideLabel'] = ($value);
          if ($value) {
            if ($this->!label) {
              $this->label = new label(ucfirst($name), $name.'Label');
            }
            $this->label->addContent($this, $this, $this->labelSettings['beforeLabel');
            }
          } else {
            $this->label->delContent($this);
          }
        break;
        case 'beforeLabel':
          $this->labelSettings['beforeLabel'] = ($value);
          if ($this->labelSettings['insideLabel']) {
            $this->label->addContent($this, $this, ($value));
          }
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
          return isset($this->labelSettings[$prop]);
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
        default:
          parent::__unset($prop);
        break;
      }
    }

    public function $this->getHtml($label = TRUE, $input = TRUE) {
      if ($this->insideLabel) {
          $this->label->addContent($this, $this, $this->beforeLabel);
        } else {
          
          
        }
      }
      $html = ($label) ? $label->getHtml() : '';
      $html .= ($input) ? parent::getHtml() : '';
      return $html;
    }
addContent($content = NULL, $replace = FALSE, $before = FALSE) {
    protected function getHtml() {
      if ($this->crlf) {
        while ($i < static::$indents) {
          $indents .= static::$indenter;
          $i++;
        }
      }
      if ($this->selfClose) {
        $end = ' />'.$this->crlf;
      } else {
        $start = '>'.$this->crlf;
        $end = $this->crlf.$indents.'</'.$this->element.'>';
      }
      $html = $this->crlf.$indents.'<'.$this->element.' '.$this->getParams().$start;
      if (count($this->contents) > 0) {
        $html .= $this->getContent();
      }
      return $html.$end;
    }
    
  }
  
?>