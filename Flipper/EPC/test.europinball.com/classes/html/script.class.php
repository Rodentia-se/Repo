<?php

  class script extends html {
    
    public function __construct($source = NULL, array $params = NULL) {
      if (!$this->settings['type'] && (substr($source, -3) == '.js' || $params['src'])) {
        $this->settings['type'] == 'file';
        $source = ($source) ? $source : $params['src'];
        $this->contentParam = 'src';
        $this->inlineBlock = TRUE;
      } else {
        $this->settings['type'] == 'code';
        $this->settings['escape'] = FALSE;
      }
      $params['type'] = ($params['type']) ? $params['type'] : 'text/javascript';
      parent::__construct('script', $source, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>