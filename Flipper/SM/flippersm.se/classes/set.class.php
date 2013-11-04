<?php
  
  class set extends match {
    
    public $match_id;
    public $machine_id;
    public $game_id;
    public $game;
    public $gameShortName;
    public $class = 'set';
    
    public static function getSelect($where = null) {
      return '
        s.id as id,
        m.id as match_id,
        s.machine_id as machine_id,
        s.game_id as game_id,
        s.game as game,
        s.gameAcronym as gameShortName,
        p1.id as player1_id,
        concat(ifnull(p1.firstName,""), " ", ifnull(p1.lastName,"")) as player1,
        p1.initials as player1_initials,
        p2.id as player2_id,
        concat(ifnull(p2.firstName,""), " ", ifnull(p2.lastName,"")) as player2,
        p2.initials as player2_initials,
        if(ifnull(s1.place,0) = 1, p1.id, if(ifnull(s2.place,0) = 1, p2.id)) as winner_id,
        if(ifnull(s1.place,0) = 1, concat(ifnull(p1.firstName,""), " ", ifnull(p1.lastName,"")), if(ifnull(s2.place,0) = 1, concat(ifnull(p2.firstName,""), " ", ifnull(p2.lastName,"")))) as winner,
        if(ifnull(s1.place,0) = 1, p1.initials, if(ifnull(s2.place,0) = 1, p2.initials)) as winner_initials
        FROM matchSet s
        LEFT JOIN match m
          ON s.match_id = m.id
        LEFT JOIN matchPlayer p1 
          ON p1.match_id = m.id
        LEFT JOIN matchScore s1 
          ON s1.matchSet_id = s.id and s1.player_id = p1.id
        LEFT JOIN matchPlayer p2
          ON p2.match_id = m.id
        LEFT JOIN matchScore s2 
          ON s2.matchSet_id = s.id and s2.player_id = p2.id
        '.$where.'
        ORDER BY m.id, s.id
      ';
    }
    
    public function __construct($data = null, $type = 'array') {
      if (preg_match("/^[0-9]+$/", $data)) {
        $sth = $dbh->prepare(self::getSelect('where m.id = '.$data));
        $sth->setFetchMode(PDO::FETCH_INTO, $this);
        $sth->execute();
        $sth->fetch(); 
      } else {
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
    }

    public function set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }

    public function display() {
  		if ($this->winner_id) {
        $winnerButton1 = $this->winner_initials;
  		} else {
  			$winnerButton1 = '<input type="button" name="'.$this->player1.'[]" value="'.$this->player1_id.'">';
  			$vinnarbutton2 = '<input type="button" name="'.$this->player2.'[]" value="'.$this->player2_id.'">';
      }
   		return '
        <div>'.$winnerButton1.'</div>
        <div class="game">'.$this->gameShortName.'</div>
        <div>'.$winnerbutton2.'</div>
      ';
    }
  }
?>