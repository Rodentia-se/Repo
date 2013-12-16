<?php

  class select extends input {
    
    public function __construct($name = NULL, $options = NULL, $selected = NULL, $label = TRUE, array $params = NULL) {
      $this->name = $name;
      $this->addOptions($options);
      $this->selectOption($selected);
      $params['data-previous'] = ($params['previous']) ? $params['previous'] : (($params['data-previous']) ? $params['data-previous'] : ((isHtml($selected)) ? $selected->value : $selected));
      parent::__construct($name, NULL, 'select', $label, $params);
    }
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    public function __get($prop) {
      switch($prop) {
        case 'options':
          return $this->getContent();
        break;
        default:
          return parent::__get($prop);
        break;
      }
    }

    public function __set($prop, $value) {
      switch($prop) {
        case 'options':
          $this->addOptions($value, NULL, TRUE);
        break;
        default:
          parent::__set($prop, $value);
        break;
      }
    }
    
    public function __isset($prop) {
      switch($prop) {
        case 'options':
          return (isset($this->contents) && count($this->contents) > 0) ? TRUE : FALSE;
        break;
        default:
          return parent::__isset($prop);
        break;
      }
    }

    public function __unset($prop) {
      switch($prop) {
        case 'options':
          $this->delOptions(TRUE);
        break;
        default:
          parent::__unset($prop);
        break;
      }
    }
    
    public function delOptions($options = NULL) {
      if (is_array($options)) {
        if (count($options) > 0) {
          foreach($options as $key => $option) {
            $this->delOptions($key);
          }
          return TRUE;
        }
      } else if (is($option)) {
        if ($option === TRUE) {
          $this->contents = array();
          return TRUE;
        } else if (array_key_exists($option, $this->contents)) {
          unset($this->contents[$option]);
          return $option;
        } else {
          if (count($this->contents) > 0) {
            foreach ($this->contents as $key => $option) {
              if ($options == $option || $options == $option->value || (isHtml($options) && $options->value == $option->value)) {
                unset($this->contents[$key]);
                return (is_int($key)) ? $key : TRUE;
              }
            }
          }
        }
      } else {
        $this->contents = array();
        return TRUE;
      }
    }
    
    public function addOptions($options = NULL, $selected = NULL, $replace = FALSE, $index = NULL) {
      if ($replace) {
        $replaced = $this->delOptions($replace);
      }
      if ($options !== NULL) {
        if (isGroup($options) || (is_array($options) && count($options) > 1)) {
          $return = TRUE;
          foreach($options as $key => $option) {
            if (isObj($option)) {
              $result = $this->addOptions(array($option->id => $option->name), $selected, FALSE, $index);
            } else if (isHtml($option)) {
              $result = $this->addOptions($option, $selected, FALSE, $index);
            } else {
              $result = $this->addOptions(array($key => $option), $selected, FALSE, $index);
            }
            if (!$result) {
              $return = FALSE;
            }
          }
          return $return;
        } else {
          if (!isHtml($options)) {
            if (is_array($options)) {
              foreach ($options as $key => $val) {
                $option = new option($val, $key);
              }
            } else {
              $option = new option($options);
            }
          } else {
            $option = $options;
          }
          if (isHtml($option)) {
            if ($index || ($replaced && $replaced !== TRUE)) {
              $index = ($index) ? $index : $replaced;
              $return = array_splice($this->contents, (($index == TRUE) ? 0 : $index), 0, array($option));
            } else {
              $return = array_push($this->contents, $option);
            }
          }
        }
      }
      if ($selected) {
        $this->selectOption($selected);
      }
    }
    
    function selectOption($selected = NULL) {
      $chosen = FALSE;
      if ($this->contents) {
        foreach ($this->contents as $key => $option) {
          if (!$chosen && $selected && ($option === $selected || $option->value == $selected || $option->getContent() == $selected)) {
            $option->selected = TRUE;
            $chosen = TRUE;
          } else {
            $option->selected = FALSE;
          }
        }
        if (!$chosen) {
          foreach ($this->contents as $key => $option) {
            if (!$chosen && $key == $selected) {
              $option->selected = TRUE;
              $chosen = TRUE;
            } else {
              $option->selected = FALSE;
            }
          }
        }
      }
      return $chosen;
    }
    
  }
  
?>