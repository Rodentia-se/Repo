<?php

  class persons extends players {
    
    public static $objClass = 'person';
    public static $all = array();
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (isPlayers($data) || isDivision($data) || isTournament($data)) {
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);  
        debug(count($data));      
        $players = players($data);
        $class = get_class($this);
        $data = new $class();
        foreach ($players as $player) {
          $data[] = $player->person;
        }
      }
      debug(count($data), 'huff');
      parent::__construct($data, $search, $depth);
    }

  }

?>