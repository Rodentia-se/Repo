<?php

  class script extends html {
    
    public function __construct($source = NULL, array $params = NULL) {
      if (substr($source, -3) == '.js' || $params['src']) {
        $this->settings['type'] == 'file';
        $source = ($source) ? $source : $params['src'];
        $this->contentParam = 'src';
      } else {
        $this->settings['type'] == 'code';
      }
      $params['type'] = ($params['type') ? $params['type'] : 'text/javascript';
      parent::__construct('script', $source, $params);
      $this->block = TRUE;
      $this->selfClose = FALSE;
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>