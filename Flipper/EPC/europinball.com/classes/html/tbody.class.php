<?php

  class tbody extends tpart {
    
    public function __construct($rows = NULL, array $params = NULL) {
      if (is_array($row)) {
        foreach ($rows as $row) {
          if ($row->type == 'thead') {
            $row->type = 'tbody';
          }
        }
      }
      parent::__construct('tbody', $rows, $params);
    }
//    tpart public function __construct($type = 'tbody', $rows = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
  }
  
?>