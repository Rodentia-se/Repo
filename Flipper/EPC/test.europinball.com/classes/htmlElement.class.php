<?php

  class htmlElement {
    
    public static $selfClosers = array('input', 'img', 'hr', 'br', 'meta', 'link');
    public static $indenter = '  ';
    public static $indent = 0;
    protected $content = array();
    protected $params = array();
    
    public function __construct($element = 'span', $content = NULL, array $params = NULL, $id = NULL) {
      $this->element = strtolower($element);
      if ($params) {
        foreach ($params as $param => $value) {
          $this->$param = $value;
          $this->params[] = $param;
        }
      }
      $this->id = ($id) ? $id : (($params['id']) ? $params['id'] : NULL);
      $this->class = (is_array($class)) ? $class : explode(' ', $class);
      $this->addContent($content);
    }
    
    public function addContent($content = NULL) {
      $this->content[] .= $content;
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
          $html = self::toHtml($part));
        }
      }
      return $html;
    }
    
    public function getParamsHtml($param = NULL) {
      if ($param) {
        if (in_array($this->params, $param)) {
          return $param.'="'.$this->$param.'"';
        } else {
          return FALSE;
        }
      } else {
        if (count($this->params) > 0 ) {
          foreach ($this->params as $name) {
            $params[] = $name.'="'.$this->$name.'"';
          }
          return implode($params, ' ');
        } else {
          return NULL;
        }
      }
    }
    
    public function getHtml($close = TRUE) {
      for ($i = 0; $i <= static::indent; $i++) {
        $indent .= static::indenter;
      }
      if (in_array($this->element, self::$selfClosers)) {
        if (!$this->value && is_scalar($this->content[0])) {
          $this->value .= $this->content[0];
          $this->params[] = 'value';
          $end = " />\n";
        }
      } else {
        $start = ">\n";
        $end = $indent."\n</".$this->elemenet.">\n";
      }
      $html = $indent.'<'.$this->element.$this->getParamsHtml().$start;
      if (count($this->content) > 0) {
        $html .= $this->getContent();
      }
      return $html.$close;
    }
    
    public function __toString() {
      return $this->getHtml();
    }
