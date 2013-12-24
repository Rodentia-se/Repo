<?php

//  namespace html;

  class html {
    
    protected $params = array();
    protected $befores = array();
    protected $headers = array();
    protected $contents = array();
    protected $footers = array();
    protected $afters = array();
    protected $classes = array();
    protected $css = array();
    protected $accessories = array();
    protected $settings = array(
      'display' => 'block',
      'hidden' => FALSE,
      'escape' => TRUE,
      'entities' => FALSE,
      'disabled' => FALSE
    );
    protected static $ids = array();
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
      $params['id'] = preg_replace('/[^a-zA-Z0-9_\-]/', '', $params['id']);
      if (!$params['id']) {
        $params['id'] = static::newId(NULL, ucfirst($this->element));
      }
      if (in_array($params['id'], html::$ids)) {
        error('Duplicate ID detected! ('.$params['id'].')', NULL, FALSE);
      } else {
        html::$ids[] = $params['id'];
      }
      $params['data-title'] = (is($params['title'])) ? $params['title'] : preg_replace('/'.ucfirst($this->element).'$/', '', ucfirst($params['id']));
      $class = mergeToArray($class, $params['class']);
      if (get_class($this) == 'html') {
        $this->selfClose = (in_array($this->element, array('input', 'img', 'hr', 'br', 'meta', 'link'))) ? TRUE : FALSE;
        $this->crlf = (in_array($this->element, array('a', 'img', 'span', 'label'))) ? NULL : "\n";
        $this->contentCrlf = (in_array($this->element, array('a', 'link', 'p', 'h1', 'h2', 'h3', 'h4', 'hr', 'br', 'li', 'input', 'img', 'span', 'label', 'option'))) ? NULL : "\n";
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
      if ($prop == $this->contentParam) {
        $prop == 'contents';
        debug('huff');
      }
      switch ($prop) {
        case 'block':
        case 'inline':
        case 'inlineBlock':
          return ($this->settings['display'] == $prop) ? TRUE : FALSE;
        break;
        case 'before':
        case 'befores':
          return $this->getBefore();
        break;
        case 'header':
        case 'headers':
          return $this->getHeader();
        break;
        case 'content':
        case 'contents':
        case $this->contentParam:
          return $this->getContent();
        break;
        case 'footer':
        case 'footers':
          return $this->getFooter();
        break;
        case 'after':
        case 'afters':
          return $this->getAfter();
        break;
        case 'title': 
          return ($this->params['title']) ? $this->params['title'] : (($this->params['data-title']) ? $this->params['data-title'] : NULL);
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
      if ($prop == $this->contentParam) {
        $prop == 'contents';
      }
      switch ($prop) {
        case 'block':
          if ($value) {
            $this->settings['display'] = 'block';
            $this->crlf = "\n";
            $this->contentCrlf = "\n";
          } else {
            $this->settings['display'] = 'inline';
            $this->crlf = '';
            $this->contentCrlf = '';
          }
        break;
        case 'inline':
          if ($value) {
            $this->settings['display'] = 'inline';
            $this->crlf = '';
            $this->contentCrlf = '';
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
            $this->contentCrlf = '';
          } else {
            $this->settings['display'] = 'block';
            $this->crlf = "\n";
            $this->contentCrlf = "\n";
          }
        break;
        case 'hidden':
          $this->hide($value);
        break;
        case 'before':
        case 'befores':
          return $this->addBefore($value, TRUE);
        break;
        case 'header':
        case 'headers':
          return $this->addHeader($value, TRUE);
        break;
        case 'content':
        case 'contents':
        case $this->contentParam:
          $this->addContent($value, TRUE);
        break;
        case 'footer':
        case 'footers':
          return $this->addFooter($value, TRUE);
        break;
        case 'after':
        case 'afters':
          return $this->addAfter($value, TRUE);
        break;
        case 'title': 
          $this->params['title'] = $value;
          $this->params['data-title'] = $value;
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
        case 'id':
          if (in_array($value, html::$ids)) {
            error('Duplicate ID detected! ('.$params['id'].')', NULL, FALSE, TRUE);
          } else {
            html::$ids[] = $value;
          }
          $this->params['id'] = preg_replace('/[^a-zA-Z0-9_\-]/', '', $value);
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
      if ($prop == $this->contentParam) {
        $prop == 'contents';
      }
      switch ($prop) { 
        case 'block':
        case 'inline':
        case 'inlineBlock':
          return ($this->settings['display'] == $prop) ? TRUE : FALSE;
        break;
        case 'before':
        case 'befores':
          return (count($this->befores) > 0) ? TRUE : FALSE;
        break;
        case 'header':
        case 'headers':
          return (count($this->headers) > 0) ? TRUE : FALSE;
        break;
        case 'content':
        case 'contents':
        case $this->contentParam:
          return (count($this->contents) > 0) ? TRUE : FALSE;
        break;
        case 'footer':
        case 'footers':
          return (count($this->footers) > 0) ? TRUE : FALSE;
        break;
        case 'after':
        case 'afters':
          return (count($this->afters) > 0) ? TRUE : FALSE;
        break;
        case 'title': 
          return ($this->params['title'] || $this->params['data-title']) ? TRUE : FALSE;
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
      if ($prop == $this->contentParam) {
        $prop == 'contents';
      }
      switch ($prop) {
        case 'block':
          $this->settings['display'] = 'inline';
          $this->crlf = '';
          $this->contentCrlf = '';
        break;
        case 'inline':
          $this->settings['display'] = 'block';
          $this->crlf = "\n";
          $this->contentCrlf = "\n";
        break;
        case 'inlineBlock':
          $this->settings['display'] = 'block';
          $this->crlf = "\n";
          $this->contentCrlf = '';
        break;
        case 'hidden':
          $this->hide(FALSE);
        break;
        case 'before':
        case 'befores':
          $this->addBefore(NULL, TRUE);
        break;
        case 'header':
        case 'headers':
          $this->addHeader(NULL, TRUE);
        break;
        case 'content':
        case 'contents':
        case $this->contentParam:
          $this->addContent(NULL, TRUE);
        break;
        case 'footer':
        case 'footers':
          $this->addFooter(NULL, TRUE);
        break;
        case 'after':
        case 'afters':
          $this->addAfter(NULL, TRUE);
        break;
        case 'title': 
          unset($this->params['title']);
          unset($this->params['data-title']);
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
        case 'id':
          html::$ids = array_diff(html::$ids, array($this->params['id']));
          unset($this->params['id']);
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
    
    protected static function newId($prefix = NULL, $suffix = NULL) {
      $id = $prefix.'id'.rand(0,10000).$suffix;
      while (in_array($id, html::$ids)) {
        $id = $prefix.'id'.rand(0,10000).$suffix;
      }
      return $id;
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
          return $return;
        } else {
          if ($index) {
            array_splice($this->$section, (($index == TRUE) ? 0 : $index), 0, array($content));
            return $content;
          } else {
            array_push($this->$section, $content);
            return $content;
          }
        }
      } else {
        return $this->del($section);
      }
    }

    protected function get($section = 'contents', $index = NULL, $string = TRUE) {
      if ($section == 'contents' && ($this->selfClose || $this->contentParam)) {
        return NULL;
      } else {
        if(is($index)) {
          $html .= ($string) ? static::contentToHtml($this->$section[$index], $this->settings['escape'], $this->settings['entities']) : $this->$section[$index];
        } else {
          if (count($this->$section) > 0) {
            if ($string) {
              foreach ($this->$section as $part) {
                $content = static::contentToHtml($part, $this->settings['escape'], $this->settings['entities']);
                if ($part->crlf && substr(trim($html, static::$indenter), strlen($this->crlf) * -1) != $this->crlf) {
                  $html .= $part->crlf;
                }
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

    public function addBefore($content = NULL, $replace = FALSE, $index = FALSE) {
      return $this->add('befores', $content, $replace, $index);
    }

    protected function getBefore($index = NULL, $string = TRUE) {
      return $this->get('befores', $index, $string);
    }

    public function delBefore($items = NULL) {
      return $this->del('befores', $items);
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
    
    public function getContent($index = NULL, $string = TRUE) {
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
      
    public function addAfter($content = NULL, $replace = FALSE, $index = FALSE) {
      return $this->add('afters', $content, $replace, $index);
    }

    protected function getAfter($index = NULL, $string = TRUE) {
      return $this->get('afters', $index, $string);
    }

    public function delAfter($items = NULL) {
      return $this->del('afters', $items);
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
          if ($param == 'style') {
             if (count($this->css) > 0) {
              $this->style = trim($this->style);
              $this->style = ($this->style && substr($this->style, -1) != ';') ? $this->style.';' : $this->style;
            }
            if ($this->style && $this->style != " ") {
              return ($string) ? 'style="'.trim($this->style.' '.$this->getCss()).'"' : array($param = trim($this->style.' '.$this->getCss()));
            } else {
              return NULL;
            }
          } else if ($param == 'class') {
            return ($string) ? 'class="'.$this->getClasses().'"' : array($this->getClasses(FALSE));
          } else if ($param == 'selected' || $param == 'checked' || $param == 'disabled') {
            return ($this->params[$param]) ? $param : FALSE;
          } else if ($param == 'id') {
            return $param.'="'.preg_replace('/[^a-zA-Z0-9_\-]/', '', $this->params[$param]).'"';
          } else if ($this->params[$param] !== '' && $this->params[$param] !== NULL) {
            return $param.'="'.$this->$param.'"';
          } else if ($this->params[$param] === FALSE) {
            return $param.'="0"';
          } else if ($this->params[$param] === TRUE) {
            return $param.'="1"';
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
              $params[] = trim($this->getParams($param));
            }
            if ($this->contentParam) {
              $params[] = $this->contentParam.'="'.$this->contents[0].'"';
            }
            return implode(array_filter($params, 'strlen'), ' ');
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
          } else if (count($this->css) > 0) {
            $this->params = array('style' => ' ');
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
        if ($props) {
          $this->css[$props] = $value;
        }
      }
      $this->params['style'] .= ' ';
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
    
    public function addMoneySpan($value = 0, $id = NULL, $format = '€ §') {
      $id = ($id) ? $id : $this->id.'MoneySpan';
      $element = $this->addSpan($value, $id, 'currency');
      $script = $this->addScriptCode('
        var num = parseInt($("#'.$id.'").html().replace(/[^0-9]/g, ""));
        $("#'.$id.'").html(num.toMoney(0, ".", " ", "", "'.$format.'"));
      ');
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

    public function addH5($contents = NULL, array $params = NULL) {
      $element = new h5($contents, $params);
      $this->addContent($element);
      return $element;
    }

    public function addH6($contents = NULL, array $params = NULL) {
      $element = new h6($contents, $params);
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

    public function addCombobox($name = NULL, $options = NULL, $selected = NULL, $label = TRUE, array $params = NULL, $selector = NULL, $indents = NULL) {
      $element = new select($name, $options, $selected, $label, $params);
      $element->addCombobox($selector, $indents);
      $this->addContent($element);
      return $element;
    }
    
    public function addInput($name = NULL, $value = NULL, $type = 'text', $label = NULL, array $params = NULL) {
      $element = new input($name, $value, $type, $label, $params);
      $this->addContent($element);
      return $element;
    }

    public function addSpinner($name = NULL, $value = NULL, $type = 'text', $label = NULL, array $params = NULL, $selector = NULL, $indents = NULL) {
      $element = new input($name, $value, $type, $label, $params);
      $element->addSpinner($selector, $indents);
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
      $this->addAfter($element);
      return $element;
    }
    
    public function addJquery($tool = NULL, $jqtype = NULL, $contents = NULL, array $props = NULL, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new jquery($selector, $tool, $jqtype, $contents, $props, $indents);
      $this->addAfter($element);
      return $element;
    }

    public function addTooltip($contents = NULL, $new = TRUE, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new tooltip($selector, $contents, $new, $indents);
      $this->addAfter($element);
      return $element;
    }

    public function addClick($code = NULL, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new click($selector, $code, $indents);
      $this->addAfter($element);
      return $element;
    }

    public function addChange($code = NULL, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new change($selector, $code, $indents);
      $this->addAfter($element);
      return $element;
    }

    public function addFocus($selector = NULL, $select = TRUE, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new focus($selector, $indents);
      $this->addAfter($element);
      if ($select) {
        $script = $this->addAfter(new selectAll($selector, $indents));
      }
      return $element;
    }
    
    public function addDialog(array $props = NULL, $selector = NULL, $indents = NULL) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $selector = (is($selector)) ? ((isHtml($selector)) ? '#'.$selector->id : $selector) : '#'.$this->id;
      $element = new dialog($selector, $props, $indents);
      $this->addAfter($element);
      return $element;
    }
    
    public function addLoading(array $props = NULL, $appendTo = NULL, $indents = 0) {
      $element = new div(html::newId('Loading'), 'modal');
      $element->addImg(config::$baseHref.'/images/ajax-loader-white.gif', 'Loading data...');
      $this->addAfter($element);
      return $element;
    }
    
    public function addTabs($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $element = new tabs($contents, $id, $class, $params);
      $this->addContent($element);
      return $element;
    }

    public function addCssFile($file = NULL, array $params = NULL) {
      $element = new cssFile($file, $params);
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
     if ($this->crlf) {
        $indents = (is($this->localIndents)) ? $this->localIndents : static::$indents;
        while ($i < $indents) {
          $indent .= static::$indenter;
          $i++;
        }
      }
      $before = $this->getBefore();
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
      $html .= $this->getHeader();
      $html .= $this->getContent();
      $html .= $this->getFooter();
      $html .= ($this->contentCrlf && substr($close, 0, strlen($this->contentCrlf)) != $this->contentCrlf && substr(trim($html, static::$indenter), strlen($this->contentCrlf) * -1) != $this->contentCrlf) ? $this->contentCrlf : '';
      $open .= ($this->contentCrlf && !$this->selfClose && substr($open, strlen($this->contentCrlf) * -1) != $this->contentCrlf && substr(trim($html, static::$indenter), 0, strlen($this->contentCrlf)) != $this->contentCrlf) ? $this->contentCrlf.$indent.static::$indenter : '';
      $after = $this->getAfter();
/*
      if ($this->settings['jsReq']) {
        $reqs = (is_array($this->settings['jsReq'])) ? $this->settings['jsReq'] : array($this->settings['jsReq']);
        foreach ($reqs as $req) {
          $js = new scriptCode('
            var loaded = $("script").filter(function () {
              var src = ($(this).attr("src")) ? $(this).attr("src").split("/") : [""] ;
              return (src[src.length - 1] == "'.$req.((substr($req, -3) != '.js') ? '.js': '').'") ? true : false;
            }).length;
            if (!loaded) {
              $("<script>").appendTo($("head"))
              .attr("type", "text/javascript")
              .attr("src", "'.config::$baseHref.'/js/contrib/'.$req.((substr($req, -3) != '.js') ? '.js': '').'");
            }
          ', NULL, $indent);
          $reqHtml .= $js->getHtml();
        }
      }
      if ($this->settings['cssReq']) {
        $reqs = (is_array($this->settings['cssReq'])) ? $this->settings['cssReq'] : array($this->settings['cssReq']);
        foreach ($reqs as $req) {
          $js = new scriptCode('
            var loaded = $("link").filter(function () {
              var href = ($(this).attr("href")) ? $(this).attr("href").split("/") : [""] ;
              return (href[href.length - 1] == "'.$req.((substr($req, -3) != '.css') ? '.css': '').'") ? true : false;
            }).length;
            if (!loaded) {
              $("<link>").appendTo($("head"))
              .attr({type : "text/css", rel : "stylesheet"})
              .attr("href", "'.config::$baseHref.'/css/contrib/'.$req.((substr($req, -3) != '.css') ? '.css': '').'");
            }
          ', NULL, $indent);
          $reqHtml .= $js->getHtml();
        }
      }
      */
      return $before.$open.$html.$close.$after;
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
