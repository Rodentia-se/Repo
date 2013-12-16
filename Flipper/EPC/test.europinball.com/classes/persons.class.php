<?php

  class persons extends players {
    
    public static $objClass = 'person';
    
    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
        debug(get_class($data), 'NO');
        debug(isPlayers($data), 'NO');
        debug(count($data), 'NO');
                debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

      if (isPlayers($data) || isDivision($data) || isTournament($data)) {
        debug(count($data), 'YES');
        parent::__construct(NULL, config::NOSEARCH, $depth);
        $players = players($data);
        debug(count($players), 'YES2');
        foreach ($players as $player) {
          $this[] = $player->person;
        }
        debug(count($this), 'YES3');
      } else {
        parent::__construct($data, $search, $depth);
      }
    }

  }

?>