<?php
  
  class division extends base {
    
    public $tournament_id;
    public $division_id;
    public $tournamentEdition_id;
    public $startDate;
    public $endDate;
    public $location_id;
    public $finalists;
    public $matchSets;
    public $matchPlayers;
    public $byes;
    public $playerArray;
    public $matchSetArray;
    public $matchArray;
    public $class = 'division';
    
    public function __construct($data = null, $type = 'array') {
      switch ($type) {
        case 'json':
          if ($data) {
            $this->set(json_decode($json, true));
          }
        break;
        case 'array':
          if ($data) {
            $this->set($data);
          }
        break;
      }
    }
    
    public function set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }

  }
?>
