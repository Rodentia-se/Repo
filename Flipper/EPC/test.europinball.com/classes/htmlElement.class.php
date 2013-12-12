<?php

  class htmlElement {
    
    public static $selfClosers = array('input', 'img', 'hr', 'br', 'meta', 'link');
    public static $noCrlf = array('img', 'span');
    public static $indenter = '  ';
    public static $crlf = "\n";
    public static $indent = 0;
    protected $params = array();
    protected $content = array();
    protected $classes = array();
    protected $css = array();
    
    public function __construct($element = 'span', $content = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL) {
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
          $this->$param = $value;
          $this->params[$param] = $value;
        }
      }
      $this->addContent($content);
    }
     
    public function addElement($element = 'span', $content = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL) {
      $el = new htmlElement($element, $content, $params, $id, $class, $css);
      $this->addContent($el);
      return $el;
    }

    public function addContent($content = NULL) {
      $this->content[] = $content;
      return TRUE;
    }
    
    public static function contentToHtml($content) {
      if (isHtmlElement($content)) {
        self::$indent++;
        $html = $content->getHtml();
        self::$indent--;
      } else if (is_array($content)) {
        self::$indent++;
        foreach ($content as $part) {
          $html .= self::toHtml($part)."\n";
        }
        self::$indent--;
      } else {
        $html = htmlspecialchars($content);
      }
      return $html;
    }
    
    public function getContentHtml($index = NULL) {
      if(is($index)) {
        $content = array($this->content[$index]);
      } else {
        $content = $this->content;
      }
      if (count($content) > 0) {
        foreach ($content as $part) {
          $html .= self::contentToHtml($part);
        }
      }
      return $html;
    }
    
    public function getClasses($string = FALSE) {
      $this->classes = mergeToArray($this->classes, $this->params['class']);
      if (count($this->classes) > 0) {
        $this->params['class'] = implode($this->classes, ' ');
      } else {
        unset($this->params['class']);
      }
      return ($string) ? $this->params['class'] : $this->classes;
    }
    
    public function getParamsHtml($param = NULL) {
      if ($param) {
        if (in_array($param, array_keys($this->params))) {
          if ($param == 'style' && count($this->css) > 0) {
            $this->style = trim($this->style);
            $this->style = ($this->style && substr($this->style, -1) != ';') ? $this->style.'; ' : $this->style;
            return 'style="'.$this->style.$this->getCssHtml().'"';
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
    
    public function getCssHtml($param = NULL) {
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

    public function getHtml() {
      $crlf = (in_array($this->element, self::$noCrlf)) ? NULL : "\n";
      if ($crlf) {
        for ($i = 0; $i <= static::$indent; $i++) {
          $indent .= static::$indenter;
        }
      }
      if (in_array($this->element, self::$selfClosers)) {
        if (!$this->value && is_scalar($this->content[0])) {
          $this->value .= $this->content[0];
          $this->params[] = 'value';
          $end = ' />'.$crlf;
        }
      } else {
        $start = '>'.$crlf;
        $end = $crlf.$indent.'</'.$this->element.'>'.$crlf;
      }
      $html = $crlf.$indent.'<'.$this->element.' '.$this->getParamsHtml().$start;
      if (count($this->content) > 0) {
        $html .= $this->getContentHtml();
      }
      return $html.$end;
    }

    public function __toString() {
      return $this->getHtml();
    }
    
  }
  
?>
