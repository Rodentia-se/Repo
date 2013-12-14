<?php

  class img extends html {
    
    public function __construct($src = NULL, $title = NULL, array $params = NULL) {
      $src = ($src) ? $src : $params['src'];
      $params['title'] = ($title) ? $title : (($params['title']) ? $params['title'] : $params['id']);
      $params['alt'] = ($params['alt']) ? $params['alt'] : $params['title'];
      $this->contentParam = 'src';
      $this->selfClose = TRUE;
      $this->inline = TRUE;
      parent::__construct('img', $src, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>