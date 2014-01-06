<?php

  class table extends html {
    
    public function __construct($rows = NULL, $headers = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $this->addHeader($headers);
      parent::__construct('table', $rows, $params, $id, $class, NULL);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    public function addHeader($content = NULL, $replace = FALSE, $index = FALSE) {
      if (@get_class($content) != 'tr') {
        $obj = $this->headers[0];
        if (@getClass($obj) == 'tr') {
          return $obj->addContent($content, $replace, $index);
        } else {
          $tr = new tr($content);
          $tr->type = 'thead';
          return $this->addHeader(new $tr, $replace, $header);
        }
      } else {
        return parent::addHeader($content, $replace, $index);
      }
    }

    public function addContent($content = NULL, $replace = FALSE, $index = FALSE) {
      if (is_array($content)) {
        if ($replace) {
          $this->delContent($replace);
        }
        $obj = $content[reset($content)];
        if (@getClass($obj) == 'tr') {
          foreach ($content as $part) {
            parent::addContent($part, NULL, $index);
            $index++;
          }
          return TRUE;
        } else {
          $tr = new tr($content);
          $tr->type = 'tbody';
          return $this->addContent($tr, $replace, $index);
        }
      } else {
        if (@get_class($content) == 'tr' {
          return parent::addContent($content, $replace, $index);
        } else {
          $obj = $this->contents[end($this->contents)];
          reset($this->contents);
          if (@getClass($obj) == 'tr') {
            return $obj->addContent($content, $replace, $index);
          } else {
            $tr = new tr($content);
            $tr->type = 'tbody';
            return $this->addContent($tr, $replace, $index);
          }
        }
      }
    }

    protected function getContent($index = NULL, $string = TRUE) {
      if ($this->contents) {
        if ($index) {
          return parent::getContent($index, $string);
        } else {
          $tbody = new tbody();
          foreach ($this->contents as $key => $content) {
            if (is_object($header) && get_class($content) == 'tr') {
              $tbody->addContent($content);
            } else {
              if (is_array($content)) {
                $tr = $tbody->addTr();
                foreach ($content as $cell) {
                  $tr->addTd($cell);
                }
              } else {
                warning('Table content object at index '.$key.' is not a table row or array! ('.get_class($header).')');
              }
            }
          }
          return ($string) ? $tbody->getHtml() : $tbody;
        }
      }
      return NULL;
    }

    protected function getHeader($index = NULL, $string = TRUE) {
      if ($this->headers) {
        if ($index) {
          return parent::getHeader($index, $string);
        } else {
          $thead = new thead();
          foreach ($this->headers as $key => $header) {
            if (is_object($header) && get_class($header) == 'tr') {
              $thead->addContent($header);
            } else {
              if (is_array($header)) {
                $tr = $thead->addTr();
                foreach ($header as $cell) {
                  $tr->addTd($cell);
                }
              } else {
                warning('Table header object at index '.$key.' is not a table row or array! ('.get_class($header).')');
              }
            }
          }
          return ($string) ? $thead->getHtml() : $thead;
        }
      }
      return NULL;
    }
    
    public function addDatatables(array $props = NULL, $indents = 0) {
      $indents = (is($indents)) ? $indents : static::$indents;
      $element = new datatables('#'.$this->id, $props, $indents);
      $this->addAfter($element);
      return $element;
    }

  }
  
?>