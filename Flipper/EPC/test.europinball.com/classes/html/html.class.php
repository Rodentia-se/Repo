<?php

//  namespace html;

  class html {
    
    protected $params = array();
    protected $headers = array();
    protected $contents = array();
    protected $footers = array();
    protected $classes = array();
    protected $css = array();
    protected $accessories = array();
    protected $settings = array(
      'display' => 'block',
      'hidden' => FALSE,
      'escape' => TRUE,
      'entities' => FALSE
    );
    public static $indenter = '  ';
    public static $indents = 0;
    public static $debugCounter = 0;
    public $localIndents;
    public $element = 'span';
    public $selfClose = FALSE;
    public $crlf = "\n";
    public $contentCrlf = "\n";
    public $contentParam = FALSE;
    
    public function __construct($element = 'html', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
      $this->element = strtolower($element);
      static::$indents = $indents;
      $params['id'] = (is($id)) ? $id : $params['id'];
      if (get_class($this) == 'html') {
        $this->selfClose = (in_array($this->element, array('input', 'img', 'hr', 'br', 'meta', 'link'))) ? TRUE : FALSE;
        $this->crlf = (in_array($this->element, array('a', 'img', 'span', 'label'))) ? NULL : "\n";
        $this->contentCrlf = (in_array($this->element, array('a', 'link', 'h1', 'h2', 'h3', 'h4', 'hr', 'br', 'li', 'input', 'img', 'span', 'label', 'option'))) ? NULL : "\n";
        $this->settings['display'] = (in_array($this->element, array('img', 'span', 'label'))) ? 'inline' : 'block';
        if (in_array($this->element, array('img', 'script'))) {
          $this->contentParam = 'src';
        } else if (in_array($this->element, array('a', 'link'))) {
          $this->contentParam = 'href';
        } else if (in_array($this->element, array('input'))) {
          $this->contentParam = 'value';
        }
      }
      $this->addParams($params);
      $this->addClasses($class);
      $this->addCss($css);
      if (is($contents)) {
        $this->addContent($contents, TRUE);
      };
    }
    
    public function __get($prop) {
      switch ($prop) {
        case 'block':
        case 'inline':
        case 'inlineBlock':
          return ($this->settings['display'] == $prop) ? TRUE : FALSE;
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
        case 'indents':
          return static::$indents;
        break;
        case 'indenter':
          return static::$indenter;
        break;
        default:
          return (array_key_exists($prop, $this->params)) ? $this->params[$prop] : ((array_key_exists($prop, $this->settings)) ? $this->settings[$prop] : NULL);
        break;
      }
    }
     
    public function __set($prop, $value) {
      switch ($prop) {
        case 'block':
          if ($value) {
            $this->settings['display'] = 'block';
            $this->crlf = "\n";
            $this->contentCrlf = "\n";
          } else {
            $this->settings['display'] = 'inline';
            unset($this->crlf);
            unset($this->contentCrlf);
          }
        break;
        case 'inline':
          if ($value) {
            $this->settings['display'] = 'inline';
            unset($this->crlf);
            unset($this->contentCrlf);
          } else {
            $this->settings['display'] = 'block';
            $this->crlf = "\n";
            $this->contentCrlf = "\n";
          }
        break;
        case 'inlineBlock':
          if ($value) {
            $this->settings['display'] = 'inline-block';
            $this->crlf = "\n";
            unset($this->contentCrlf);
          } else {
            $this->settings['display'] = 'block';
            $this->crlf = "\n";
            $this->contentCrlf = "\n";
          }
        break;
        case 'hidden':
          $this->hide($value);
        break;
        case 'content':
        case 'contents':
        case $this->contentParam:
          $this->addContent($value, TRUE);
        break;
        case 'class': 
        case 'classes': 
          $this->addClasses($value, TRUE);
        break;
        case 'css':
          $this->addCss($value, NULL, TRUE);
        break;
        case 'html':
          return FALSE;
        break;
        case 'indents':
          static::$indents = $value;
        break;
        case 'indenter':
          static::$indenter = $value;
        break;
        default:
          if (array_key_exists($prop, $this->settings)) {
            $this->settings[$prop] = $value;
          } else {
            $this->params[$prop] = $value;
          }
        break;
      }
    }
    
    public function __isset($prop) {
      switch ($prop) { 
        case 'block':
        case 'inline':
        case 'inlineBlock':
          return ($this->settings['display'] == $prop) ? TRUE : FALSE;
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
        case 'indents':
          return isset(static::$indents);
        break;
        case 'indenter':
          return isset(static::$indenter);
        break;
        default:
          return (array_key_exists($prop, $this->params)) ? isset($this->params[$prop]) : ((array_key_exists($prop, $this->settings)) ? isset($this->settings[$prop]) : FALSE);
        break;
      }
    }
    
    public function __unset($prop) {
      switch ($prop) {
        case 'block':
          $this->settings['display'] = 'inline';
          unset($this->crlf);
          unset($this->contentCrlf);
        break;
        case 'inline':
          $this->settings['display'] = 'block';
          $this->crlf = "\n";
          $this->contentCrlf = "\n";
        break;
        case 'inlineBlock':
          $this->settings['display'] = 'block';
          $this->crlf = "\n";
          unset($this->contentCrlf);
        break;
        case 'hidden':
          $this->hide(FALSE);
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
        case 'indents':
          unset(static::$indents);
        break;
        case 'indenter':
          unset(static::$indenter);
        break;
        default:
          if (array_key_exists($prop, $this->params)) {
            unset($this->params[$prop]);
          } else if (array_key_exists($prop, $this->settings)) {
            unset($this->settings[$prop]);
          }
        break;
      }
    }
    
    protected static function getIndent($type = 'indent') {
      switch ($type) {
        case 'indenter':
          return static::$indenter;
        break;
        case 'indents':
          return static::$indents;
        break;
        case 'indent':
        default:
          while ($i < static::$indents) {
            $indent .= static::$indenter;
            $i++;
          }
          return $indent;
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
    
    protected function add($section = 'contents', $content = NULL, $replace = FALSE, $index = FALSE) {
      if ($replace) {
        $return = $this->del($section, $replace);
      }
      if ($content !== NULL) {
        if (is_array($content)) {
          $return = TRUE;
          foreach($content as $part) {
            $result = $this->add($section, $part, FALSE, $index);
            if (!$result) {
              $return = FALSE;
            }
          }
        } else {
          if ($index) {
            $return = array_splice($this->$section, (($index == TRUE) ? 0 : $index), 0, array($content));
          } else {
            $return = array_push($this->$section, $content);
          }
        }
      } else {
        $return = $this->del($section);
      }
      return $return;
    }

    protected function get($section = 'contents', $index = NULL, $string = TRUE) {
      if (!$this->selfClose) {
        if(is($index)) {
          $html .= ($string) ? static::contentToHtml($this->$section[$index], $this->settings['escape'], $this->settings['entities']) : $this->$section[$index];
        } else {
          if (count($this->$section) > 0) {
            if ($string) {
              foreach ($this->$section as $part) {
                $content = static::contentToHtml($part, $this->settings['escape'], $this->settings['entities']);
                if ($html && substr(trim($html, static::$indenter), strlen($this->crlf) * -1) == $this->crlf && $content && substr($content, 0, strlen($this->crlf)) == $this->crlf) {
                  $html .= substr($content, strlen($this->crlf));
                } else {
                  $html .= $content;
                }
              }
            } else {
              return $this->$section;
            }
          }
        }
      }
      return $html;
    }

    protected function del($section = 'contents', $items = NULL) {
      if (count($this->$section) > 0) {
        if (is($item) && $item !== TRUE) {
          if (array_key_exists($item, $this->$section)) {
            unset($this->$section[$item]);
            return TRUE;
          } else {
            $items = (is_array($items)) ? $items : array($items);
            $this->$section = array_diff($this->$section, $items);
            return TRUE;
          }
        } else {
          $this->$section = array();
          return TRUE;
        }
      }
      return FALSE;
    }

    public function addHeader($content = NULL, $replace = FALSE, $index = FALSE) {
      return $this->add('headers', $content, $replace, $index);
    }

    protected function getHeader($index = NULL, $string = TRUE) {
      return $this->get('headers', $index, $string);
    }

    public function delHeader($items = NULL) {
      return $this->del('headers', $items);
    }

    public function addContent($content = NULL, $replace = FALSE, $index = FALSE) {
      return $this->add('contents', $content, $replace, $index);
    }
    
    protected function getContent($index = NULL, $string = TRUE) {
      return $this->get('contents', $index, $string);
    }
    
    public function delContent($items = NULL) {
      return $this->del('contents', $items);
    }
    
    public function addFooter($content = NULL, $replace = FALSE, $index = FALSE) {
      return $this->add('footers', $content, $replace, $index);
    }

    protected function getFooter($index = NULL, $string = TRUE) {
      return $this->get('footers', $index, $string);
    }

    public function delFooter($items = NULL) {
      return $this->del('footers', $items);
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
      return TRUE;
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
          } else if (($param == 'selected' || $param == 'checked') && $this->params[$param]) {
            return $param;
          } else if ($this->params[$param] != '') {
            return $param.'="'.$this->$param.'"';
          } else {
            return FALSE;
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
      return TRUE;
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
      return TRUE;
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

    public function addImg($src = NULL, $title = NULL, array $params = NULL) {
      $element = new img($src, $title, $params);
      $this->addContent($element);
      return $element;
    }

    public function addLink($url = NULL, $contents = 'link', array $params = NULL) {
      $element = new link($url, $contents, $params);
      $this->addContent($element);
      return $element;
    }

    public function addParagraph($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $element = new paragraph($contents, $id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addH1($contents = NULL, array $params = NULL) {
      $element = new h1($contents, $params);
      $this->addContent($element);
      return $element;
    }

    public function addH2($contents = NULL, array $params = NULL) {
      $element = new h2($contents, $params);
      $this->addContent($element);
      return $element;
    }

    public function addH3($contents = NULL, array $params = NULL) {
      $element = new h3($contents, $params);
      $this->addContent($element);
      return $element;
    }

    public function addH4($contents = NULL, array $params = NULL) {
      $element = new h4($contents, $params);
      $this->addContent($element);
      return $element;
    }

    public function addBr($id = NULL, $class = NULL, array $params = NULL) {
      $element = new br($id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addHr($id = NULL, $class = NULL, array $params = NULL) {
      $element = new hr($id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addUl($id = NULL, $class = NULL, array $params = NULL) {
      $element = new ul($id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addOl($id = NULL, $class = NULL, array $params = NULL) {
      $element = new ol($id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addLi($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $element = new li($contents, $id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addForm($id = NULL, $action = NULL, $method = 'POST', array $params = NULL) {
      $element = new form($id, $action, $method, $params);
      $this->addContent($element);
      return $element;
    }

    public function addLabel($contents = NULL, $for = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $element = new label($contents, $for, $id, $class, $params);
      $this->addContent($element);
      return $element;
    }
  
    public function addSelect($name = NULL, $options = NULL, $selected = NULL, $label = TRUE, array $params = NULL) {
      $element = new select($name, $options, $selected, $label, $params);
      $this->addContent($element);
      return $element;
    }

    public function addOption($text = NULL, $value = NULL, $selected = FALSE, array $params = NULL) {
      $element = new option($text, $value, $selected, $params);
      $this->addContent($element);
      return $element;
    }

    public function addInput($name = NULL, $value = NULL, $type = 'text', $label = NULL, array $params = NULL) {
      $element = new input($name, $value, $type, $label, $params);
      $this->addContent($element);
      return $element;
    }

    public function addHidden($name = NULL, $value = 'yes', array $params = NULL) {
      $element = new hidden($name, $value, $params);
      $this->addContent($element);
      return $element;
    }

    public function addCheckbox($name = NULL, $checked = FALSE, array $params = NULL) {
      $element = new checkbox($name, $checked, $params);
      $this->addContent($element);
      return $element;
    }

    public function addRadio($name = NULL, $value = NULL, $checked = FALSE, array $params = NULL) {
      $element = new radio($name, $value, $checked, $params);
      $this->addContent($element);
      return $element;
    }

    public function addButton($value = 'submit', $name = NULL, array $params = NULL) {
      $element = new button($value, $name, $params);
      $this->addContent($element);
      return $element;
    }
    
    public function addClickButton($value = 'submit', $name = NULL, $url = NULL, $form = TRUE, $script = TRUE, array $params = NULL) {
      $element = new clickButton($value, $name, $url, $form, $script, $params);
      $this->addContent($element);
      return $element;
    }

    public function addScript($source = NULL, array $params = NULL) {
      $element = new script($source, $params);
      $this->addContent($element);
      return $element;
    }

    public function addScriptFile($file = NULL, array $params = NULL) {
      $element = new scriptFile($file, $params);
      $this->addContent($element);
      return $element;
    }
    
    public function addScriptCode($code = NULL, array $params = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $element = new scriptCode($code, $params, $indents);
      $this->addContent($element);
      return $element;
    }
    
    public function addJquery($tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new jquery($selector, $tool, $jqtype, $contents, $props, $indents);
      $this->addContent($element);
      return $element;
    }

    public function addTooltip($contents = NULL, $new = TRUE, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new tooltip($selector, $contents, $new, $indents);
      $this->addContent($element);
      return $element;
    }

    public function addClick($code = NULL, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new click($selector, $code, $indents);
      $this->addContent($element);
      return $element;
    }

    public function addCssFile($file = NULL, array $params = NULL) {
      $element = new cssFile($code, $params);
      $this->addContent($element);
      return $element;
    }
    
    public function hide($hidden = TRUE) {
      if ($hidden) {
        $this->addCss('display', 'none');
      } else {
        $this->delCss('display', 'none');
      }
        $this->settings['hidden'] = $hidden;
    }

    protected static function contentToHtml($content, $escape = TRUE, $entities = FALSE) {
      if (isHtml($content)) {
        static::$indents++;
        $html = $content->getHtml();
        static::$indents--;
      } else if (is_array($content)) {
        foreach ($content as $part) {
          $html .= static::contentToHtml($part, $escape, $entities);
        }
      } else {
        $html = ($entities) ? htmlentities($content) : (($escape) ? htmlspecialchars($content) : $content);
      }
      return $html;
    }
    
    public function getHtml() {
     $this->debug(get_class($this), 'CLASS', NULL, 150);
     if ($this->crlf) {
        $indents = (is($this->localIndents)) ? $this->localIndents : static::$indents;
        while ($i < $indents) {
          $indent .= static::$indenter;
          $i++;
        }
      }
      $openStart = $this->crlf.$indent.'<';
      if ($this->selfClose) {
        $closeEnd = ' />'.$this->crlf;
      } else {
        $openEnd = '>';
        $closeStart = (($this->contentCrlf) ? $indent : '').'</'.$this->element;
        $closeEnd = '>';
      }
      $open = $openStart.$this->element;
      $params = $this->getParams();
      $open .= (($params) ? ' ' : '').$params.$openEnd;
      $close = $closeStart.$closeEnd;
      if ($this->headers) {
        $html .= $this->getHeader();
      }
      if ($this->contents) {
        $html .= $this->getContent();
      } 
      if ($this->footers) {
        $html .= $this->getFooter();
      }
      $html .= ($this->contentCrlf && substr($close, 0, strlen($this->contentCrlf)) != $this->contentCrlf && substr(trim($html, static::$indenter), strlen($this->contentCrlf) * -1) != $this->contentCrlf) ? $this->contentCrlf : '';
      $open .= ($this->contentCrlf && !$this->selfClose && substr($open, strlen($this->contentCrlf) * -1) != $this->contentCrlf && substr(trim($html, static::$indenter), 0, strlen($this->contentCrlf)) != $this->contentCrlf) ? $this->contentCrlf.$indent.static::$indenter : '';
      return $open.$html.$close;
    }

    public function __toString() {
      return $this->getHtml();
    }
    
    protected function debug($obj, $title, $die, $stop) {
      static::$debugCounter++;
      if ($stop && static::$debugCounter >= $stop) {
        $die = TRUE;
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
      }
      debug($obj, $title, $die);
    }
    
  }
  
?>
