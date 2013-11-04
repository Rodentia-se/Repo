<?php
  
  class division extends base {
    
    public $tournamentEdition_id;
    public $startDate;
    public $endDate;
    public $location_id;
    public static $finalists;
    public static $matchSets;
    public static $matchPlayers;
    public static $byes;
    public $playerArray;
    public $setArray;
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

  	public function getSetArray() {
  		$this->getSets($dbh);
  		return $this->setArray;
  	}
    
  	public function getNoc() {
  		return self::$finalists;
  	}

  	public function setNoc($noc) {
  		self::$finalists = $noc;
  	}

  	
  	public function getByes() {
  		return self::$byes;
  	}

  	public function setByes($byes) {
  		self::$byes = $byes;
  	}

  	protected function getSets($dbh) {    
      $sth = $dbh->query(set::getSelect('where m.tournamentDivision_id = '.$this->id));
      while ($obj = $sth->fetchObject('set')) {
        $objs[] = $obj;
      }
      return $objs;
    }

  	protected function getMatches($dbh) {    
      $sth = $dbh->query(match::getSelect('where m.tournamentDivision_id = '.$this->id));
      while ($obj = $sth->fetchObject('match')) {
        $objs[] = $obj;
      }
      return $objs;
    }

  }
?>
