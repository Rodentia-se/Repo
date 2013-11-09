<?php
// class tournament.php

class tournament {

	public static $competitionid = 1;
	public static $numberOfCompetitors = 55;
	public static $numberOfSets = 3;
	public static $creamfiles = 0;

	public $spelarArray;
	public $setArray;
	public $matchArray;

	// Set appropriate tablenames to use in this class.
	const SPELTABELL = 'Pinballentry_game';
	const SPELARTABELL = 'spelare';
	const SETTABELL = 'sets';



/*	public function __construct($noc) {

		if ((is_numeric($noc)) && ($noc > 0)) {
			$this->numberOfCompetitors = $noc;
		}
		
	}
*/
	public function getSetArray() {

		$this->getSets();
		return $this->setArray;

	}

	public function getNoc() {
		return self::$numberOfCompetitors;
	}

	public function setNoc($noc) {
		self::$numberOfCompetitors = $noc;
	}

	
	public function getCreamfiles() {
		return self::$creamfiles;
	}

	public function setCreamfiles($cf) {
		self::$creamfiles = $cf;
	}



	protected function getSets() {

		// Skapa en kontakt med databasen
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT matchID, setID, playerA, playerB, spel, vinnare, SpA.Tag AS TagA, SpB.Tag AS TagB, Game.Abbreviation AS Abbr 
						  FROM " . self::SETTABELL . "
						  LEFT JOIN " . self::SPELARTABELL . " AS SpA ON sets.playerA = SpA.SpelarID
						  LEFT JOIN " . self::SPELARTABELL . " AS SpB ON sets.playerB = SpB.SpelarID
						  LEFT JOIN " . self::SPELTABELL . " AS Game ON sets.spel = Game.Gameid
						  ORDER BY matchID, setID");
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			$this->setArray['matchID'][] = $row['matchID']; 
			$this->setArray['setID'][] = $row['setID']; 
			$this->setArray['playerA'][] = $row['playerA']; 
			$this->setArray['playerB'][] = $row['playerB']; 
			$this->setArray['spel'][] = $row['spel']; 
			$this->setArray['tagA'][] = $row['TagA']; 
			$this->setArray['tagB'][] = $row['TagB']; 
			$this->setArray['vinnare'][] = $row['vinnare']; 
			$this->setArray['abbr'][] = $row['Abbr'];
		
		}

	}


}

?>
