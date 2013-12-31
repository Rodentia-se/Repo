<?php

  class matchScores extends group {
    
    public static $objClass = 'matchScore';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }
    
  }

?>