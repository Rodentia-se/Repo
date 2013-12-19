<?php

  class persons extends players {
    
    public static $objClass = 'person';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      if (isPlayers($data) || isDivision($data) || isTournament($data)) {
        $players = players($data);
        $class = get_class($this);
        $data = new $class();
        foreach ($players as $player) {
          $data[] = $player->person;
        }
      }
      parent::__construct($data, $search, $depth);
    }

  }

?>