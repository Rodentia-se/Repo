<?php

  class tr extends html {
    
    public $type = 'tbody';

    public function __construct($cells = NULL, array $params = NULL) {
      debug('tr');
      parent::__construct('tr', $cells, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    public function addContent($content = NULL, $replace = FALSE, $index = FALSE) {
      debug($content);
      $class = ($this->type == 'thead') ? 'th' : 'td';
      if (is_array($content)) {
        if ($replace) {
          $this->delContent($replace);
        }
        foreach ($content as $part) {
          $this->addContent($part, NULL, $index);
          $index++;
        }
      } else if (@get_class($content) != $class) {
        return $this->addContent(new $class($content), $replace, $header);
      } else {
        return parent::addContent($content, $replace, $index);
      }
    }

  }
  
?>