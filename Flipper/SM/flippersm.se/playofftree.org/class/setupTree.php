<?php
// class setupTree.php
session_start();

include_once "tournament.php";



class setupTree {

	public $spelarArray;
	protected $setsInMatch;
	protected $noc;

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

		$STH = $pdo->prepare("SELECT Rank, SpelarID, Tag, Namn FROM " . tournament::SPELARTABELL . " ORDER BY Rank LIMIT 0, " . tournament::$numberOfCompetitors . "");
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {

			$this->spelarArray['rank'][] = $row['Rank']; 
			$this->spelarArray['spelarid'][] = $row['SpelarID']; 
			$this->spelarArray['tag'][] = $row['Tag']; 
			$this->spelarArray['namn'][] = $row['Namn']; 

		}

	}



	private function putInPlace() {

		// Ordning för förstespelaren när det är sexton, åtta eller fyra matcher.
		$ordning16 = array(1,16,9,8,5,12,13,4,3,14,11,6,7,10,15,2);
		$ordning8 = array(1,8,5,4,3,6,7,2);
		$ordning4 = array(1,4,3,2);
		$ordning2 = array(1,2);


		// Depending of no of Creamfiles, how many players in first round?
		if (tournament::$creamfiles == 0) {
			$siffra = $this->noc;
		}
		if (tournament::$creamfiles == 1) {
			echo "bulle";
		}
		if (tournament::$creamfiles == 2) {
			$siffra = $this->noc/2;
		}

		// Sort the spelarArray according to the order of ordningX.
//		array_multisort(${"ordning" . $siffra}, $this->spelarArray['rank'], $this->spelarArray['spelarid'], $this->spelarArray['tag'], $this->spelarArray['namn']);
		# var_dump($this->spelarArray);
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

		for ($i = 1; $i <= (tournament::$numberOfCompetitors/2); $i++) {

			$pida = $this->spelarArray['spelarid'][$playerA];
			$pidb = $this->spelarArray['spelarid'][$playerB];

			$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " (matchID, competitionID, playerA, playerB) VALUES ('$i', '" . tournament::$competitionid . "', '$pida','$pidb')");

			for ($j = 0 ; $j < $this->setsInMatch; $j ++ ) {
				$STH->execute();

				// Funkade det att registrera matchen?
		        if($STH->rowCount() != 1) {
					throw new Exception('Något blev fel. Matchen registrerades inte i databasen.' );
		        }

			}

			$playerA += 2;
			$playerB += 2;
		}
	}

}

?>
