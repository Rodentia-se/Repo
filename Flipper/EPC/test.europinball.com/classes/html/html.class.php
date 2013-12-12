<?php

//  namespace html;

  class html {
    
    protected $params = array();
    protected $contents = array();
    protected $classes = array();
    protected $css = array();
    protected $display = 'block';
    protected $hidden = FALSE;
    public static $selfClosers = array('input', 'img', 'hr', 'br', 'meta', 'link');
    public static $noCrlfs = array('img', 'span');
    public static $valuers = array('input');
    public static $srcrs = array('img', 'script');
    public static $indenter = '  ';
    public static $indents = 0;
    public $localIndents;
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
      unset($this->localIndents);
      if (is($id)) {
        $params['id'] = $id;
      }
      $this->addParams($params);
      $this->addClasses($class);
      $this->addCss($css);
      $this->addContent($contents, TRUE);
    }
    
    public function __get($prop) {
      switch ($prop) {
        case 'block':
        case 'inline':
          return ($this->display == $prop);
        break;
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
        case 'block':
          if ($value) {
            $this->display = 'block';
            $this->crlf = "\n";
          } else {
            $this->display = 'inline';
            unset($this->crlf);
          }
        break;
        case 'inline':
          if ($value) {
            $this->display = 'inline';
            unset($this->crlf);
          } else {
            $this->display = 'block';
            $this->crlf = "\n";
          }
        break;
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
        case 'block':
        case 'inline':
          return ($this->display == $prop);
        break;
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
        case 'block':
          $this->display = 'inline';
          unset($this->crlf);
        break;
        case 'inline':
          $this->display = 'block';
          $this->crlf = "\n";
        break;
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
      if (!isHtml($element)) {
        $element = new html($element, $contents, $params, $id, $class, $css);
      }
      $this->addContent($element);
      return $element;
    }
    
    public function delElement($elements) {
      if (count($this->contents) > 0) {
        $elements = (is_array($elements)) ? $elements : array($elements);
        $this->contents = array_diff($this->contents, $elements);
        return TRUE;
      }
      return FALSE;
    }

    public function addContent($content = NULL, $replace = FALSE, $before = FALSE) {
      if ($replace) {
        $this->delContent($replace);
      }
      if ($content !== NULL) {
        if (is_array($content)) {
          foreach($content as $part) {
            $this->addContent($part, FALSE, $before);
          }
        } else {
          if ($before) {
            array_unshift($this->contents, $content);
          } else {
            $this->contents[] = $content;
          }
        }
      } else {
        $this->delContent();
      }
      return $this->getContent();
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
    
    public function delContent($items = NULL) {
      if (count($this->contents) > 0) {
        if (is($item) && $item !== TRUE) {
          if (array_key_exists($item, $this->contents)) {
            unset($this->contents[$item]);
            return TRUE;
          } else {
            $items = (is_array($items)) ? $items : array($items);
            $this->contents = array_diff($this->contents, $items);
            return TRUE;
          }
        } else {
          $this->contents = array();
        }
      }
      return FALSE;
    }
    
    public function addParams($props = NULL, $value = NULL, $replace = FALSE) {
      if ($replace) {
        $this->delParams($replace);
      }
      if (isAssoc($props)) {
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
    
    function delParams($params = NULL, $value = NULL) {
      if ($params == $this->contentParam && (!is($value) || $this->contents[0] == $value)) {
          $this->contents = array();
          return TRUE;
      } else {
        if (count($this->params) > 0) {
          if (is($params) && $param !== TRUE) {
            if (array_key_exists($params, $this->params) && (!is($value) || $this->params[$params] == $value)) {
              unset($this->params[$params]);
            }
          } else {
            $this->params = array();
            if ($this->contentParam) {
              $this->contents[0] = array();
            }
          }
          return TRUE;
        }
      }
      return FALSE;
    }

    public function addClasses($classes = NULL, $replace = FALSE) {
      if ($replace) {
        $this->delClasses($replace);
      }
      if ($classes !== NULL) {
        $this->classes = mergeToArray($this->classes, $classes);
      } else {
        $this->delClasses();
      }
      if (count($this->classes) > 0) {
        $this->params['class'] = implode($this->classes, ' ');
      } else {
        unset($this->params['class']);
      }
      return $this->getClasses();
    }
    
    protected function getClasses($class = NULL, $string = TRUE) {
      if ($class) {
        return (in_array($class, $this->classes)) ? (($string) ? $class : TRUE) : FALSE;
      } else {
        return ($string) ? $this->params['class'] : $this->classes;
      }
    }
    
    function delClasses($classes = NULL) {
      if (count($this->classes) > 0) {
        if (is($classes) && $classes !== TRUE) {
          if (array_key_exists($classes, $this->classes)) {
            unset($this->classes[$classes]);
          } else {
            $classes = (is_array($classes)) ? $classes : explode(' ', $classes);
            $this->classes = array_diff($this->classes, $classes);
          }
        } else {
          $this->classes = array();
        }
        if (count($this->classes) > 0) {
          $this->params['class'] = implode($this->classes, ' ');
        } else {
          unset($this->params['class']);
        }
      }
      return FALSE;
    }

    public function addCss($props = NULL, $value = NULL, $replace = FALSE) {
      if ($replace) {
        $this->delCss($replace);
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

    public function delCss($props = NULL, $value = NULL) {
      if (count($this->css) > 0) {
        if (is($props) && $props !== TRUE) {
          if (!is($value) && array_key_exists($props, $this->css)) {
            unset($this->css[$props]);
            return TRUE;
          } else {
            foreach ($this->css as $param => $val) {
              if (is($value)) {
                if ($props.': '.$value.';' == $param.': '.$val.';') {
                  $this->delCss($param);
                } 
              } else if ($props == $param.': '.$val.';') {
                $this->delCss($param);
              }
            }
          }
        } else {
          $this->css = array();
        }
        return TRUE;
      }
      return FALSE;
    }
    
    public function addDiv($id = NULL, $class = NULL, array $params = NULL) {
      $element = new div($id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addSpan($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $element = new span($contents, $id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addForm($id = NULL, $action = NULL, $method = 'POST', array $params = NULL) {
      $element = new form($id, $action, $method, $params);
      $this->addContent($element);
      return $element;
    }

    public function addInput($name = NULL, $value = NULL, $type = 'text', $label = NULL, array $params = NULL) {
      $element = new input($name, $value, $type, $label, $params);
      $this->addContent($element);
      return $element;
    }

    public function addButton($value = 'submit', $name = NULL, array $params = NULL) {
      $element = new button($value, $name, $params);
      $this->addContent($element);
      return $element;
    }

    public function addLabel($contents = NULL, $for = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $element = new label($contents, $for, $id, $class, $params);
      $this->addContent($element);
      return $element;
    }
  
    public function hide($hidden = TRUE) {
      if ($hidden) {
        $this->addCss('display', 'none');
      } else {
        $this->delCss('display', 'none');
      }
        $this->hidden = $hidden;
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
    
    protected function getHtml() {
      if ($this->crlf) {
        $indents = (is($this->localIndents)) ? static::$indents : static::$indents;
        while ($i < $indents) {
          $indent .= $indenter;
          $i++;
        }
      }
      if ($this->selfClose) {
        $end = ' />'.$this->crlf;
      } else {
        $start = '>';
        $end = $this->crlf.$indent.'</'.$this->element.'>';
      }
      $html = $this->crlf.$indent.'<'.$this->element.' '.$this->getParams().$start;
      if (count($this->contents) > 0) {
        $html .= $this->crlf.$indent.$this->getContent();
      }
      return $html.$end;
    }

    public function __toString() {
      return $this->getHtml();
    }
    
  }
  
?>
