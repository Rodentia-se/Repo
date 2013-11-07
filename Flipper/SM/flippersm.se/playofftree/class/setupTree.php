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

		$STH = $pdo->prepare("SELECT place, person_id, initials, firstName, lastName FROM " . tournament::SPELARTABELL . " WHERE tournamentDivision_id = " . tournament::$competitionid . " ORDER BY place LIMIT 0, " . tournament::$numberOfCompetitors);
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {

			$this->spelarArray['place'][] = $row['place']; 
			$this->spelarArray['person_id'][] = $row['person_id']; 
			$this->spelarArray['initials'][] = $row['initials']; 
			$this->spelarArray['firstname'][] = $row['firstName'];
			$this->spelarArray['lastname'][] = $row['lastName']; 
			
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

				$firstname = $this->spelarArray['firstname'][$anummer-1];
				$lastname = $this->spelarArray['lastname'][$anummer-1];
				$initials = $this->spelarArray['initials'][$anummer-1];
				
				// The first round has two players in each match...
				if ($i <= ($this->noc/(2 + tournament::$creamfiles))) {

					$pida = $this->spelarArray['person_id'][$anummer-1];				
					$pidb = $this->spelarArray['person_id'][$bnummer-1];

					// Insert values into db-table matchplayer, one row per match, one row per player
					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHPLAYERTABELL . " 
										(match_id, player_id, firstName, lastName, initials) 
										VALUES ('$i', '$pida','$firstname', '$lastname', '$initials')");

					$STH->execute();

					// Insert values for the second player into db-table matchplayer.
					$firstname = $this->spelarArray['firstname'][$bnummer-1];
					$lastname = $this->spelarArray['lastname'][$bnummer-1];
					$initials = $this->spelarArray['initials'][$bnummer-1];

					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHPLAYERTABELL . " 
										(match_id, player_id, firstName, lastName, initials) 
										VALUES ('$i', '$pidb','$firstname', '$lastname', '$initials')");

					$STH->execute();

					// Insert values into db-table match, one row per match.
					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHTABELL . " 
										(id, tournamentDivision_id, round, sets) 
										VALUES ('$i', 'tournament::$competitionid',1, '$this->setsInMatch')");

					$STH->execute();

					// Prepare to insert values into table matchSet
					$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " 
										(match_id) 
										VALUES ('$i')");


					for ($j = 0 ; $j < $this->setsInMatch; $j ++ ) {

						// And populate table matchSet three (or apropriate number) times per match.
						$STH->execute();

					}

					$playerA += 2;
					$playerB += 2;

				} 
				// ... but the following rounds only has one player waiting.
				else {

					$round = round( $i / ($this->noc/2 + tournament::$creamfiles), 0, PHP_ROUND_HALF_DOWN);

					$pidb = $this->spelarArray['person_id'][$anummer-1];

					// Insert values into db-table matchplayer, one row per match.
					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHPLAYERTABELL . " 
										(match_id, player_id, firstName, lastName, initials) 
										VALUES ('$i', '$pidb','$firstname', '$lastname', '$initials')");

					$STH->execute();

					// Insert values into db-table match, one row per match.
					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHTABELL . " 
										(id, tournamentDivision_id, round, sets) 
										VALUES ('$i', '" . tournament::$competitionid . "','$round','$this->setsInMatch')");

					$STH->execute();

					// Prepare to insert values into table matchSet
					$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " 
										(match_id) 
										VALUES ('$i')");

					for ($j = 0 ; $j < $this->setsInMatch; $j ++ ) {
						$STH->execute();
					}

					$playerA ++;
				}				

			}

		}
		else {

			$incer = $this->noc/4; // Two variables for calculating the actual round.
			$round = 1;
			
			for ($i = 1; $i <= ($this->noc/2); $i++) {

				// Get which round it is.			
				$incer = $incer + ($this->noc/2) / pow(2, $round);
				if ($i > $incer) {
					$round++;
					$incer = $incer + ($noc) / pow(2, $round+1);
				}

				// Get the actual positions from the array.
				$anummer = $this->{"ordning" . $this->noc}[$playerA];
				$bnummer = $this->{"ordning" . $this->noc}[$playerB];

				$pida = $this->spelarArray['person_id'][$anummer-1];
				$pidb = $this->spelarArray['person_id'][$bnummer-1];

					// Insert values into db-table matchplayer, one row per match, one row per player
					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHPLAYERTABELL . " 
										(match_id, player_id, firstName, lastName, initials) 
										VALUES ('$i', '$pida','$firstname', '$lastname', '$initials')");

					$STH->execute();

					// Insert values for the second player into db-table matchplayer.
					$firstname = $this->spelarArray['firstname'][$bnummer-1];
					$lastname = $this->spelarArray['lastname'][$bnummer-1];
					$initials = $this->spelarArray['initials'][$bnummer-1];

					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHPLAYERTABELL . " 
										(match_id, player_id, firstName, lastName, initials) 
										VALUES ('$i', '$pidb','$firstname', '$lastname', '$initials')");

					$STH->execute();

					// Insert values into db-table match, one row per match.
					$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHTABELL . " 
										(id, tournamentDivision_id, round, sets) 
										VALUES ('$i', '" . tournament::$competitionid . "','$round', '$this->setsInMatch')");

					$STH->execute();

					// Prepare to insert values into table matchSet
					$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " 
										(match_id) 
										VALUES ('$i')");


					for ($j = 0 ; $j < $this->setsInMatch; $j ++ ) {

						// And populate table matchSet three (or apropriate number) times per match.
						$STH->execute();

					}

				$playerA += 2;
				$playerB += 2;

			}

		}
	}
}

?>
