<?php

  class ifpaPerson extends person {

    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      debug($data, 'DATA');
      debug($search, 'DATA');
      debug($depth, 'DATA');
    }
  
  }

?>