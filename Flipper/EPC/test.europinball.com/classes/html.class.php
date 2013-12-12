<?php

  class html {
    
    protected $params = array();
    protected $contents = array();
    protected $classes = array();
    protected $css = array();
    public static $selfClosers = array('input', 'img', 'hr', 'br', 'meta', 'link');
    public static $noCrlfs = array('img', 'span');
    public static $valuers = array('input');
    public static $srcrs = array('img', 'script');
    public static $indenter = '  ';
    public static $indents = 0;
    public $crlf = "\n";
    public $element = 'span';
    
    public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      $this->element = strtolower($element);
      $this->indents = $indents;
      if (is($id)) {
        $params['id'] = $id;
      }
      $this->addParams($params);
      debug($this->params);
      $this->addClasses($class);
      $this->addCss($css);
      $this->addContent($contents);
    }
    
    public function __get($prop) {
      switch ($prop) {
        case 'content':
        case 'contents':
          return $this->getContent();
        break;
        case 'class': 
        case 'classes': 
          return $this->getClasses();
        break;
        case 'css':
          return $this->getCss();
        break;
        case 'html':
          return $this->getHtml();
        break;
        default:
          return (array_key_exists($prop, $this->params)) ? $this->params[$prop] : NULL;
        break;
      }
    }
     
    public function __set($prop, $value) {
      switch ($prop) {
        case 'src':
          if (in_array($this->element, static::$srcrs)) {
            return $this->addContent($value, TRUE);
          }
          return $this->params[$prop] = $value;
        break;
        case 'value':
          if (in_array($this->element, static::$valuers)) {
            return $this->addContent($value, TRUE);
          }
          return $this->params[$prop] = $value;
        break;
        case 'content':
        case 'contents':
          return $this->addContent($value, TRUE);
        break;
        case 'class': 
        case 'classes': 
          return $this->addClasses($value, TRUE);
        break;
        case 'css':
          return $this->addCss($value, NULL, TRUE);
        break;
        case 'html':
          return FALSE;
        break;
        default:
          debug($prop, 'prop');
          debug($value, 'val');
          return $this->params[$prop] = $value;
        break;
      }
    }
    
    public function __isset($prop) {
      switch ($prop) { 
        case 'content':
        case 'contents':
          if (in_array($this->element, static::$srcrs)) {
            return (array_key_exists('src', $this->params)) ? isset($this->params['src']) : FALSE;
          } else if (in_array($this->element, static::$valuers)) {
            return (array_key_exists('value', $this->params)) ? isset($this->params['value']) : FALSE;
          }
          return (count($this->contents) > 0) ? TRUE : FALSE;
        break;
        case 'class': 
        case 'classes': 
          return (count($this->classes) > 0) ? TRUE : FALSE;
        break;
        case 'css':
          return (count($this->css) > 0) ? TRUE : FALSE;
        break;
        case 'html':
          return TRUE;
        break;
        default:
          return (array_key_exists($prop, $this->params)) ? isset($this->params[$prop]) : FALSE;
        break;
      }
    }
    
    public function __unset($prop) {
      switch ($prop) {
        case 'src':
          if (in_array($this->element, static::$srcrs)) {
            $this->addContent(NULL, TRUE);
          }
          unset($this->params['src']);
        break;
        case 'value':
          if (in_array($this->element, static::$valuers)) {
            $this->addContent(NULL, TRUE);
          }
          unset($this->params['value']);
        break;
        case 'content':
        case 'contents':
          $this->addContent(NULL, TRUE);
        break;
        case 'class': 
        case 'classes': 
          $this->addClasses(NULL, TRUE);
        break;
        case 'css':
          $this->addCss(NULL, NULL, TRUE);
        break;
        case 'html':
          return FALSE;
        break;
        default:
          unset($this->params[$prop]);
        break;
      }
    }
    
    public function addElement($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL) {
      $el = new html($element, $contents, $params, $id, $class, $css);
      $this->addContent($el);
      return $el;
    }

    public function addContent($content = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->contents);
        if (in_array($this->element, static::$srcrs)) {
          unset($this->src);
        }
        if (in_array($this->element, static::$valuers)) {
          unset($this->value);
        }
      }
      if ($content !== NULL) {
        $this->contents[] = $content;
        if (in_array($this->element, static::$srcrs)) {
          $this->src = $content;
        }
        if (in_array($this->element, static::$valuers)) {
          $this->value = $content;
        }
      }
      return TRUE;
    }
    
    public function addClasses($classes = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->classes);
      }
      if (is_array($classes)) {
        foreach ($classes as $class) {
          $this->addClasses($class);
        }
      } else {
        $this->classes[] = $classes;
      }
      return $this->getClasses();
    }

    public function addCss($props = NULL, $value = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->css);
      }
      if (isAssoc($props)) {
        foreach ($props as $prop => $val) {
          $this->addCss($prop, $val);
        }
      } else {
        $this->css[$props] = $value;
      }
      return $this->getCss();
    }

    public function addParams($props = NULL, $value = NULL, $replace = FALSE) {
      if (isAssoc($props)) {
        if ($replace) {
          foreach ($this->params as $prop => $val) {
            $this->__unset($prop);
          }
        }
        foreach ($props as $prop => $val) {
          $this->addParams($prop, $val);
        }
      } else {
        $this->__set($props, $value);
      }
      return $this->getParams();
    }

    protected static function contentToHtml($content) {
      if (isHtml($content)) {
        self::$indents++;
        $html = $content->getHtml();
        self::$indents--;
      } else if (is_array($content)) {
        foreach ($content as $part) {
          $html .= static::contentToHtml($part);
        }
      } else {
        $html = htmlspecialchars($content);
      }
      return $html;
    }
    
    protected function getContent($index = NULL, $string = TRUE) {
      if (!in_array($this->element, static::$selfClosers)) {
        if(is($index)) {
          $html .= ($string) ? static::contentToHtml($this->contents[$index]) : $this->contents[$index];
        } else {
          if (count($this->contents) > 0) {
            if ($string) {
              foreach ($this->contents as $part) {
                $html .= static::contentToHtml($part);
              }
            } else {
              return $this->contents;
            }
          }
        }
      }
      return $html;
    }
    
    protected function getClasses($class = NULL, $string = TRUE) {
      if ($class) {
        return (in_array($class, $this->classes)) ? (($string) ? 'true' : TRUE) : (($string) ? 'false' : FALSE);
      }
      $this->classes = mergeToArray($this->classes, $this->class);
      if (count($this->classes) > 0) {
        $this->class = implode($this->classes, ' ');
      } else {
        unset($this->class);
      }
      return ($string) ? $this->class : $this->classes;
    }
    
    protected function getParams($param = NULL, $string = TRUE) {
      if ($param) {
        if (in_array($param, array_keys($this->params))) {
          if ($param == 'style' && count($this->css) > 0) {
            $this->style = trim($this->style);
            $this->style = ($this->style && substr($this->style, -1) != ';') ? $this->style.';' : $this->style;
            return ($string) ? 'style="'.trim($this->style.' '.$this->getCss()).'"' : array($param = trim($this->style.' '.$this->getCss()));
          } else if ($param == 'class') {
            return ($string) ? 'class="'.$this->getClasses(TRUE).'"' : array($this->getClasses());
          } else {
            return $param.'="'.$this->$param.'"';
          }
        } else {
          return FALSE;
        }
      } else {
        if (count($this->params) > 0 ) {
          if ($string) {
            foreach ($this->params as $param => $value) {
              $params[] = $this->getParams($param);
            }
            return implode($params, ' ');
          } else {
            return $this->params;
          }
        } else {
          return NULL;
        }
      }
    }
    
    protected function getCss($param = NULL, $string = TRUE) {
      if ($param) {
        return (array_key_exists($this->css, $param)) ? (($string) ? $param.': '.$this->css[$param].';' : array($param => $this->css[$param])) : FALSE;
      } else {
        if (count($this->css) > 0) {
          if ($string) {
            foreach ($this->css as $param => $value) {
              $css[] .= $param.': '.$value.';';
            }
            return (implode($css, ' '));
          } else {
            return $this->css;
          }
        } else {
          return NULL;
        }
      }
    }

    protected function getHtml() {
      $crlf = (in_array($this->element, static::$noCrlfs)) ? NULL : $this->crlf;
      if ($crlf) {
        while ($i < static::$indents) {
          $indents .= static::$indenter;
          $i++;
        }
      }
      if (in_array($this->element, static::$selfClosers)) {
        if (in_array($this->element, static::$valuers)) {
          if (!$this->value && is_scalar($this->contents[0])) {
            $this->value = $this->contents[0];
          }
        } 
        if (in_array($this->element, static::$srcrs)) {
          if (!$this->src && is_scalar($this->contents[0])) {
            $this->src = $this->contents[0];
          }
        } 
        $end = ' />'.$crlf;
      } else {
        $start = '>'.$crlf;
        $end = $crlf.$indents.'</'.$this->element.'>'.$crlf;
      }
      $html = $crlf.$indents.'<'.$this->element.' '.$this->getParams().$start;
      if (count($this->contents) > 0) {
        $html .= $this->getContent();
      }
      return $html.$end;
    }

    public function __toString() {
      return $this->getHtml();
    }
    
  }
  
?>
