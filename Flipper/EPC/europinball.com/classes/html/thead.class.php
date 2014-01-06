<?php

  class thead extends tpart {
    
    public function __construct($rows = NULL, array $params = NULL) {
      if (is_array($row)) {
        foreach ($rows as $row) {
          if ($row->type == 'tbody') {
            $row->type = 'thead';
          }
        }
      }
      parent::__construct('thead', $rows, $params);
    }
//    tpart public function __construct($type = 'tbody', $rows = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>