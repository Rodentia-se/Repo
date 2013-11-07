<?php
// class tournament.php

class tournament {

	public static $competitionid = 1;
	public static $numberOfCompetitors = 2;
	public static $numberOfSets = 3;
	public static $creamfiles = 0;

	public $spelarArray;
	public $setArray;
	public $matchArray;

	// Set appropriate tablenames to use in this class.
	const SPELTABELL = 'game';
	const SPELARTABELL = 'player';
	const SETTABELL = 'matchSet';
	const MATCHTABELL = 'match';
	const MATCHPLAYERTABELL = 'matchPlayer';
	const MATCHSCORETABELL = 'matchScore';




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


	protected function getSets($compID = 1) {

		// Skapa en kontakt med databasen
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT Match.id AS Matchid, 
									sets.id AS setid, 
									sets.machine_id, 
									sets.gameAcronym,
									mp1.initials AS TagA, 
									mp2.initials AS TagB,
									mp1.player_id AS pidA,
									mp2.player_id AS pidB,
									ms1.Score AS scoreA,
									ms2.Score AS scoreB 
								FROM " . self::MATCHTABELL . " AS Match 
								LEFT JOIN " . self::SETTABELL . " AS sets ON sets.match_id = Match.id
								LEFT JOIN (
    									SELECT * 
    									FROM " . self::MATCHPLAYERTABELL . " 
    									WHERE mp1.order = 1) mp1
											ON Match.id = mp1.match_id
								LEFT JOIN (
    									SELECT * 
    									FROM " . self::MATCHPLAYERTABELL . " 
    									WHERE mp2.order = 2) mp2
											ON Match.id = mp2.match_id
								LEFT JOIN (
										SELECT Score
										FROM " . self::MATCHSCORETABELL . "
										WHERE ms1.player_id = pidA) ms1
											ON ms1.matchSet_id = sets.id
								LEFT JOIN (
										SELECT Score
										FROM " . self::MATCHSCORETABELL . "
										WHERE ms2.player_id = pidB) ms2
											ON ms2.matchSet_id = sets.id
								WHERE tour_id = $compID");
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			$this->setArray['matchID'][] = $row['matchid']; 
			$this->setArray['setID'][] = $row['setid']; 
			$this->setArray['playerA'][] = $row['pidA']; 
			$this->setArray['playerB'][] = $row['pidB']; 
			$this->setArray['spel'][] = $row['machine_id']; 
			$this->setArray['tagA'][] = $row['TagA']; 
			$this->setArray['tagB'][] = $row['TagB']; 
			if ($row['scoreA'] > $row['scoreB'])
				$this->setArray['vinnare'][] = $row['pidA'];
			else 
				$this->setArray['vinnare'][] = $row['pidB'];
			$this->setArray['abbr'][] = $row['gameAcronym'];
		
		}

	}


}

?>
