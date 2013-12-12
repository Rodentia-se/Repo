<?php

  class htmlElement {
    
    public static $selfClosers = array('input', 'img', 'hr', 'br', 'meta', 'link');
    public static $noCrlf = array('img', 'span',);
    public static $indenter = '  ';
    public static $crlf = "\n";
    public static $indent = 0;
    protected $content = array();
    public $css = array();
    
    public function __construct($element = 'span', $content = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL) {
      $this->element = strtolower($element);
      if (isAssoc($params)) {
        foreach ($params as $param => $value) {
          $this->$param = $value;
          $this->params[] = $param;
        }
      }
      $this->id = ($id) ? $id : (($params['id']) ? $params['id'] : NULL);
      $this->class = (is_array($class)) ? $class : explode(' ', $class);
      if (isAssoc($css) && count($css) > 0) {
        $this->params[] = 'style';
        foreach ($css as $param => $value) {
          $this->css[$param] = $value;
        }
      }
      $this->addContent($content);
    }
    
    public function addContent($content = NULL) {
      $this->content[] .= $content;
      debug($this->content, 'cont');
      return TRUE;
    }
    
    public static function toHtml($content) {
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
      debug($html, 'spec');
      }
      return $html;
    }
    
    public function getContentHtml($index = NULL) {
      debug($index, 'index');
      if(is($index)) {
        $content = array($this->content[$index]);
      debug($content, 'part1');
      } else {
        $content = $this->content;
      debug($content, 'part2');
      }
      if (count($content) > 0) {
        foreach ($content as $part) {
          $html = self::toHtml($part);
      debug($part, 'part');
        }
      }
      return $html;
    }
    
    public function getParamsHtml($param = NULL) {
      if ($param) {
        if (in_array($param, $this->params) && property_exists($this, $param)) {
          if ($name == 'style' && count($this->css) > 0) {
            return 'style="'.$this->style.(($this->style && substr($this->style, -1) != ';') ? ';' : '').$this->getCssHtml().'"';
          } else {
            return $param.'="'.$this->$param.'"';
          }
        } else {
          return FALSE;
        }
      } else {
        if (count($this->params) > 0 ) {
          foreach ($this->params as $param) {
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

    public function getHtml($close = TRUE) {
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
      $html = $indent.'<'.$this->element.' '.$this->getParamsHtml().$start;
      if (count($this->content) > 0) {
        $html .= $this->getContentHtml();
              debug($html, 'html');
      }
      return $html.$end;
    }
    
    public function __toString() {
      return $this->getHtml();
    }
    
  }
  
?>
