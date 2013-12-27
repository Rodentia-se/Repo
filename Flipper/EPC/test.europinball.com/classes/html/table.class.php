<?php

  class table extends html {
    
    public function __construct($rows = NULL, $headers = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      $this->addHeader($headers);
      parent::__construct('table', $rows, $params, $id, $class, NULL);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    protected function getContent($index = NULL, $string = TRUE) {
      if ($this->contents) {
        if ($index) {
          return parent::getContent($index, $string);
        } else {
          $tbody = new tbody();
          foreach ($this->contents as $key => $content) {
            if (get_class($content) == 'tr') {
              $tbody->addTr($content);
            } else {
              warning('Table body object at index '.$key.' is not a table row! ('.get_class($content).')');
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
            if (get_class($header) == 'tr') {
              $thead->addTr($header);
            } else {
              warning('Table header object at index '.$key.' is not a table row! ('.get_class($header).')');
            }
          }
          return ($string) ? $thead->getHtml() : $thead;
        }
      }
      return NULL;
    }

  }
  
?>