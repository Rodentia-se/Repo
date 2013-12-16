<?php

  class persons extends players {
    
    public static $objClass = 'person';
    
    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      if (isPlayers($data) || isDivision($data) || isTournament($data)) {
        parent::__construct(NULL, config::NOSEARCH, $depth);
        $players = players($data);
        foreach ($players as $player) {
          $this[] = $player->person;
        }
      } else {
        parent::__construct($data, $search, $depth);
      }
    }

  }

?>