<?php
  
  class set extends match {
    
    public $tournamentEdition_id;
    public $tournamentDivision_id;
    public $player1_id;
    public $player2_id;
    public $player1;
    public $player2;
    public $player1_initials;
    public $player2_initials;
    public $winner_id;
    public $winner;
    public $winner_initials;
    public $setArray;
    public $matchSets;
    public $score;
    public $place;
    public $class = 'match';
    
    public static function getSelect($where = null) {
      return '
        m.id as id,
        m.sets as matchSets,
        p1.id as player1_id,
        concat(ifnull(p1.firstName,""), " ", ifnull(p1.lastName,"")) as player1,
        p1.initials as player1_initials,
        p2.id as player2_id,
        concat(ifnull(p2.firstName,""), " ", ifnull(p2.lastName,"")) as player2,
        p2.initials as player2_initials,
        if(ifnull(p1.place,0) = 1, p1.id, if(ifnull(p2.place,0) = 1, p2.id)) as winner_id,
        if(ifnull(p1.place,0) = 1, concat(ifnull(p1.firstName,""), " ", ifnull(p1.lastName,"")), if(ifnull(p2.place,0) = 1, concat(ifnull(p2.firstName,""), " ", ifnull(p2.lastName,"")))) as winner,
        if(ifnull(p1.place,0) = 1, p1.initials, if(ifnull(p2.place,0) = 1, p2.initials)) as winner_initials
        FROM match m
        LEFT JOIN matchPlayer p1 
          ON p1.match_id = m.id
        LEFT JOIN matchPlayer p2
          ON p2.match_id = m.id
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
    
  	protected function getSets($dbh) {    
      $sth = $dbh->query(set::getSelect('where m.id = '.$this->id));
      while ($obj = $sth->fetchObject('set')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
   	public function display($dbh) {
      $players = $this->getPlayers($dbh);
      foreach ($players as $player) {
    		$content .= '
          <div class="tag">'.$player->initials.'</div>
          <div class="fullname">'.$player->name.'</div>
          <div class="tag">'.$player->initials.'</div>
          <div class="fullname">'.$player->name.'</div>
        ';
      }
      $sets = $this->getSets($dbh);
  		foreach ($sets as $set) {
        $content .= $set->display();
      }
      return $content;
  	}

  }
?>