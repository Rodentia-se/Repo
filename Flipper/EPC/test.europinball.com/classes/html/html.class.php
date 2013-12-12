<?php

//  namespace html;

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
    public $element = 'span';
    public $selfClose;
    public $crlf;
    public $contentParam;
    
    public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      $this->element = strtolower($element);
      $this->selfClose = (isset($this->selfClose)) ? $this->selfClose : ((in_array($this->element, static::$selfClosers)) ? TRUE : FALSE);
      $this->crlf = (isset($this->crlf)) ? $this->crlf : ((in_array($this->element, static::$noCrlfs)) ? NULL : "\n");
      $this->contentParam = (isset($this->contentParam)) ? $this->contentParam : ((in_array($this->element, static::$valuers)) ? 'value' : ((in_array($this->element, static::$srcrs)) ? 'src' : NULL));
      static::$indents = $indents;
      if (is($id)) {
        $params['id'] = $id;
      }
      $this->addParams($params);
      debug($this->params);
      $this->addClasses($class);
      $this->addCss($css);
      $this->addContent($contents, TRUE);
    }
    
    public function __get($prop) {
      switch ($prop) {
        case 'content':
        case 'contents':
        case $this->contentParam:
          return $this->getContent();
        break;
        case 'class': 
          return $this->getClasses(NULL, TRUE);
        break;
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
        case 'content':
        case 'contents':
        case $this->contentParam:
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
          return $this->params[$prop] = $value;
        break;
      }
    }
    
    public function __isset($prop) {
      switch ($prop) { 
        case 'content':
        case 'contents':
        case $this->contentParam:
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
        case 'content':
        case 'contents':
        case $this->contentParam:
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
      if (!isHtml($el)) {
        $el = new html($element, $contents, $params, $id, $class, $css);
      }
      $this->addContent($el);
      return $el;
    }

    public function addContent($content = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->contents);
      }
      if ($content !== NULL) {
        $this->contents[] = $content;
      } else {
        unset($this->contents);
      }
      return TRUE;
    }
    
    public function addClasses($classes = NULL, $replace = FALSE) {
      if ($replace) {
        unset($this->classes);
      }
      $this->classes = mergeToArray($this->classes, $classes);
      if (count($this->classes) > 0) {
        $this->params['class'] = implode($this->classes, ' ');
      } else {
        unset($this->classes);
        unset($this->params['class']);
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
          if ($this->contentParam) {
            unset($this->contents);
          } else {
            unset($this->params);
          }
        }
        foreach ($props as $prop => $val) {
          $this->addParams($prop, $val);
        }
      } else {
        if ($props == $this->contentParam) {
          $this->contents[0] = $value;
        } else {
          $this->params[$props] = $value;
        }
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
        return (in_array($class, $this->classes)) ? (($string) ? $class : TRUE) : FALSE;
      } else {
        return ($string) ? $this->params['class'] : $this->classes;
      }
    }
    
    protected function getParams($param = NULL, $string = TRUE) {
      if ($param) {
        if (in_array($param, array_keys($this->params))) {
          if ($param == 'style' && count($this->css) > 0) {
            $this->style = trim($this->style);
            $this->style = ($this->style && substr($this->style, -1) != ';') ? $this->style.';' : $this->style;
            return ($string) ? 'style="'.trim($this->style.' '.$this->getCss()).'"' : array($param = trim($this->style.' '.$this->getCss()));
          } else if ($param == 'class') {
            return ($string) ? 'class="'.$this->getClasses().'"' : array($this->getClasses(FALSE));
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
            if ($this->contentParam) {
              $params[] = $this->contentParam.'="'.$this->contents[0].'"';
            }
            return implode($params, ' ');
          } else {
            $params = $this->params;
            $params[$this->contentParam] = $this->contents[0];
            return $params;
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

    public function __toString() {
      return $this->getHtml();
    }
    
  }
  
?>
