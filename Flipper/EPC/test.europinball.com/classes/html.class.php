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
    
    public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      $this->element = strtolower($element);
      if (is($id)) {
        $params['id'] = $id;
      }
      $this->classes = mergeToArray($class, $params['class']);
      $this->params['class'] = implode($this->classes, ' ');
      if (isAssoc($css) && count($css) > 0) {
        foreach ($css as $param => $value) {
          $this->css[$param] = $value;
          $params['style'] = ($params['style']) ? $params['style'] : ' ';
        }
      }
      if (isAssoc($params) && count($params) > 0) {
        foreach ($params as $param => $value) {
          $this->params[$param] = $value;
        }
      }
      $this->indents = $indents;
      $this->addContent($contents);
    }
    
    public function __get($prop) {
      switch ($prop) {
        case 'content':
        case 'contents':
          return $this->getContentHtml();
        break;
        case 'class': 
        case 'classes': 
          return $this->getClasses();
        break;
        case 'css':
          return $this->getCssHtml();
        break;
        case 'html':
          return $this->getHtml();
        break;
        default:
          return (array_key_exists($prop, $this->params)) ? $this->params[$prop] : NULL;
        break;
      }
    }
     
    public function __set($prop, $data) {
      switch ($prop) {
        case 'src':
          if (in_array($this->element, static::$srcrs)) {
            return $this->addContent($data, TRUE);
          }
          return $this->params[$prop];
        break;
        case 'value':
          if (in_array($this->element, static::$valuers)) {
            return $this->addContent($data, TRUE);
          }
          return $this->params[$prop];
        break;
        case 'content':
        case 'contents':
          return $this->addContent($data, TRUE);
        break;
        case 'class': 
        case 'classes': 
          return $this->addClass($data, TRUE);
        break;
        case 'css':
          return $this->addCss($data, NULL, TRUE);
        break;
        case 'html':
          return FALSE;
        break;
        default:
          return $this->params[$prop];
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
            return $this->addContent(NULL, TRUE);
          }
          return unset($this->params['src']);
        break;
        case 'value':
          if (in_array($this->element, static::$valuers)) {
            return $this->addContent(NULL, TRUE);
          }
          return unset($this->params['value']);
        break;
        case 'content':
        case 'contents':
          return $this->addContent(NULL, TRUE);
        break;
        case 'class': 
        case 'classes': 
          return $this->addClass(NULL, TRUE);
        break;
        case 'css':
          return $this->addCss(NULL, NULL, TRUE);
        break;
        case 'html':
          return FALSE;
        break;
        default:
          return unset($this->params[$prop]);
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
          unset($this->params['src']);
        }
        if (in_array($this->element, static::$valuers)) {
          unset($this->params['value']);
        }
      }
      if ($contents !== NULL) {
        $this->contents[] = $content;
        if (in_array($this->element, static::$srcrs)) {
          $this->params['src'] = $content;
        }
        if (in_array($this->element, static::$valuers)) {
          $this->params['value'] = $content;
        }
      }
      return TRUE;
    }
    
    public function addClass($class = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->classes);
      }
      if (is_array($class)) {
        foreach ($class as $className) {
          $this->addClass($className)
        }
      } else {
        $this->classes[] = $class;
      }
      return $this->getClasses();
    }

    public function addCss($prop = NULL, $value = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->css);
      }
      if (isAssoc($prop)) {
        $this->css = $prop
      } else {
        $this->css[$prop] = $value;
      }
      return $this->getClasses();
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
              debug($html, 'html');
      return $html;
    }
    
    protected function getContentHtml($index = NULL) {
      if (!in_array($this->element, static::$selfClosers)) {
        if(is($index)) {
          $html .= static::contentToHtml($this->contents[$index]);
        } else {
          if (count($this->contents) > 0) {
            foreach ($this->contents as $part) {
              $html .= static::contentToHtml($part);
            }
          }
        }
      }
      return $html;
    }
    
    protected function getClasses($string = FALSE) {
      $this->classes = mergeToArray($this->classes, $this->params['class']);
      if (count($this->classes) > 0) {
        $this->params['class'] = implode($this->classes, ' ');
      } else {
        unset($this->params['class']);
      }
      return ($string) ? $this->params['class'] : $this->classes;
    }
    
    protected function getParamsHtml($param = NULL) {
      if ($param) {
        if (in_array($param, array_keys($this->params))) {
          if ($param == 'style' && count($this->css) > 0) {
            $this->params['style'] = trim($this->params['style']);
            $this->params['style'] = ($this->params['style'] && substr($this->params['style'], -1) != ';') ? $this->params['style'].';' : $this->params['style'];
            return 'style="'.trim($this->params['style'].' '.$this->getCssHtml()).'"';
          } else if ($param == 'class') {
            return 'class="'.$this->getClasses(TRUE).'"';
          } else {
            return $param.'="'.$this->params[$param].'"';
          }
        } else {
          return FALSE;
        }
      } else {
        if (count($this->params) > 0 ) {
          foreach ($this->params as $param => $value) {
            $params[] = $this->getParamsHtml($param);
          }
          return implode($params, ' ');
        } else {
          return NULL;
        }
      }
    }
    
    protected function getCssHtml($param = NULL) {
      if ($param) {
        return (array_key_exists($this->css, $param)) ? $param.': '.$this->css[$param].';' : FALSE;
      } else {
        if (count($this->css) > 0) {
          foreach ($this->css as $param => $value) {
            $css[] .= $param.': '.$value.';';
          }
          return (implode($css, ' '));
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
          if (!$this->params['value'] && is_scalar($this->contents[0])) {
            $this->params['value'] = $this->contents[0];
          }
        } 
        if (in_array($this->element, static::$srcrs)) {
          if (!$this->params['src'] && is_scalar($this->contents[0])) {
            $this->params['src'] = $this->contents[0];
          }
        } 
        $end = ' />'.$crlf;
      } else {
        $start = '>'.$crlf;
        $end = $crlf.$indents.'</'.$this->element.'>'.$crlf;
      }
      $html = $crlf.$indents.'<'.$this->element.' '.$this->getParamsHtml().$start;
      if (count($this->contents) > 0) {
        $html .= $this->getContentHtml();
      }
      return $html.$end;
    }

    public function __toString() {
      return $this->getHtml();
    }
    
  }
  
?>
