<?php
// class setupTree.php
session_start();

include_once "tournament.php";
include_once "functions/creamorder.php";



class setupTree {

	public $spelarArray;
	protected $setsInMatch;
	protected $noc;
	protected $ordning2;
	protected $ordning4;
	protected $ordning8;
	protected $ordning16;
	protected $ordning32;
	protected $ordning64;


	public function __construct() {

		$this->noc = tournament::$numberOfCompetitors;
		$this->getData();
		$this->putInPlace();

	}

	public function getSpelarArray() {

		#$this->getData();
		return $this->spelarArray;	

	}


	protected function getData() {

		// Skapa en kontakt med databasen
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT Rank, SpelarID, Tag, Namn FROM " . tournament::SPELARTABELL . " ORDER BY Rank LIMIT 0, " . tournament::$numberOfCompetitors);
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {

			$this->spelarArray['rank'][] = $row['Rank']; 
			$this->spelarArray['spelarid'][] = $row['SpelarID']; 
			$this->spelarArray['tag'][] = $row['Tag']; 
			$this->spelarArray['namn'][] = $row['Namn']; 

		}

	}

	private function putInPlace() {

		$siffra = $this->noc;

		// Order for the players for different sizes.
		$this->ordning64 = array(1,64,32,33,17,48,16,49,9,56,24,41,25,40,8,57,
							5,60,28,37,21,44,12,53,13,52,20,45,29,36,4,61,
							3,62,30,35,19,46,51,14,11,54,22,43,27,38,6,59,
							7,58,26,39,23,42,10,55,15,50,18,47,31,34,2,63);
		$this->ordning32 = array(1,32,16,17,8,25,9,24,4,29,13,20,5,28,12,21,
							3,30,14,19,6,27,11,22,10,23,7,26,15,18,2,31);
		$this->ordning16 = array(1,16,9,8,5,12,13,4,3,14,11,6,7,10,15,2);
		$this->ordning8 = array(1,8,5,4,3,6,7,2);
		$this->ordning4 = array(1,4,3,2);
		$this->ordning2 = array(1,2);

		// Re-arrange the array if it's creamfiles, when the worst ranked plays each other first.
		if (tournament::$creamfiles == 2) {
			
			$this->{"ordning" . $siffra} = creamOrder($this->{"ordning" . $siffra}, 2);

		}

	}

	public function insertSets() {

		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$playerA = 0;
		$playerB = 1;

		$this->setsInMatch = $_SESSION['sim'];

		if (tournament::$creamfiles == 2) {

			$times = ($this->noc - ($this->noc/(2 + tournament::$creamfiles)));

			for ($i = 1; $i <= $times; $i++) {

				// Get the actual positions from the array.
				$anummer = $this->{"ordning" . $this->noc}[$playerA];
				$bnummer = $this->{"ordning" . $this->noc}[$playerB];

				
				// The first round has two players in each match...
				if ($i <= (tournament::$numberOfCompetitors/(2 + tournament::$creamfiles))) {

					$pida = $this->spelarArray['spelarid'][$anummer-1];				
					$pidb = $this->spelarArray['spelarid'][$bnummer-1];

					$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " 
										(matchID, competitionID, playerA, playerB) 
										VALUES ('$i', '" . tournament::$competitionid . "', '$pida','$pidb')");

					for ($j = 0 ; $j < $this->setsInMatch; $j ++ ) 
						$STH->execute();

					$playerA += 2;
					$playerB += 2;

				} 
				// ... but the following rounds only has one player waiting.
				else {

					$pidb = $this->spelarArray['spelarid'][$anummer-1];

					$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " 
										(matchID, competitionID, playerB) 
										VALUES ('$i', '" . tournament::$competitionid . "', '$pidb')");

					for ($j = 0 ; $j < $this->setsInMatch; $j ++ )
						$STH->execute();
					

					$playerA ++;
				}				

			}

		}
		else {
			
			for ($i = 1; $i <= (tournament::$numberOfCompetitors/2); $i++) {

				$pida = $this->spelarArray['spelarid'][$playerA];
				$pidb = $this->spelarArray['spelarid'][$playerB];

				$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " 
									(matchID, competitionID, playerA, playerB) 
									VALUES ('$i', '" . tournament::$competitionid . "', '$pida','$pidb')");

				for ($j = 0 ; $j < $this->setsInMatch; $j ++ )
					$STH->execute();

			}

			$playerA += 2;
			$playerB += 2;
		}
	}
}

?>
