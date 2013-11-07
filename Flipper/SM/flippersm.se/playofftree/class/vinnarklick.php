<?php
// vinnarklick.php
session_start();

include_once "tournament.php";
include "uppkoppling.php";

// Sätt all felrapportering PÅ.
   ini_set('display_errors',1);
   ini_set('display_startup_errors',1);
   error_reporting(E_ALL ^ E_NOTICE);


class vinnarklick {

	protected $vinnarstring;
	protected $set;
	protected $sid;
	public $result;
	protected $matchid;
	protected $avancemang = 0;
	protected $noc;
	protected $setsInMatch;
	private $creamfiles;

	public function __construct() {

		$this->vinnarstring = mysql_real_escape_string($_GET['button']);

		preg_match("/set([0-9]+)/", $this->vinnarstring, $this->set);

		preg_match("/sid([0-9]+)/", $this->vinnarstring, $this->sid);

		$this->noc = $_SESSION['noc'];
		$this->setsInMatch = $_SESSION['sim'];
		$this->creamfiles = $_SESSION['creamfiles'];

		$this->setVinnare();

		$this->gotAWinner();
	}


	protected function setVinnare() {

		$this->result = "Setid: " . $this->set[1] . " Spelarid: " . $this->sid[1];
		echo $this->result;

		// Establish connection with the database
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("UPDATE " . tournament::MATCHSCORETABELL . " SET
							  score = 1  
							  WHERE setID = {$this->set[1]} AND matchPlayer_id = {$this->sid[1]}");

		$STH->execute();

	}


	protected function gotAWinner() {

		// Establish connection with the database
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		// Get winners from each set that belongs to the newly updated match.
		$STH = $pdo->prepare("SELECT score, player_id, match_id FROM " . tournament::MATCHSCORETABELL . " 
							  WHERE " . tournament::MATCHSCORETABELL . ".matchSet_id = {$this->set[1]})");
		$STH->execute();
		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			if ($row['score'] == 1)
				$vinnare[] = $row['player_id'];
			$this->matchid = $row['match_id'];
		}

		// Variables for playerID and counters.
		$e = $vinnare[0];
		$a = 0;
		$ena = 0;
		$andra = 0;

		// echo "stopp: " . $vinnare;

		foreach ($vinnare as $v) {

			if ($v == $e) {
				$ena++;
				echo "<p>V: " . $v . " ena: " . $ena;
			}
			else {
				$andra++;
				$a = $v;
				echo "<p>V: " . $v . " andra: " . $andra;
			}
		}

		// Decide if any got enough sets to win
		if ($ena > ($this->setsInMatch/2))
				$this->avancemang = $e;
		if ($andra > ($this->setsInMatch/2))
				$this->avancemang = $a;

		$this->newMatch();

	}


	protected function newMatch() {

		// Calculate the next matchid in the tree
		if (($this->creamfiles == 2) && ($this->matchid <= $this->noc-($this->noc/2))) {

			$theNewMatchID = $this->matchid + ($this->noc / (2 + $this->creamfiles));
			// echo "BULTTUUTAN";
			$whichplayer = "playerA";
		}
		else {

			$theNewMatchID = ((($this->noc - $this->matchid)/2) + $this->matchid);
			$theNewMatchID = round($theNewMatchID);

			echo "<p>Nya matchid:t " . $theNewMatchID;
			echo "<p>NOC: " . $this->noc;
			echo "<p>Avanc: " . $this->avancemang;
	
			echo "creamfiles: " . tournament::$creamfiles;

			// If this player should be on top... (previous matchid was odd)
			if ($this->matchid&1)
				$ordning = 1;
			else 
				$ordning = 2;

		}

		// Check if it exists.
		$connect = new uppkoppling();
		$pdo = $connect->conn();
		$sql = "SELECT COUNT(*) FROM " . tournament::SETTABELL . " WHERE match_id = $theNewMatchID"; 
		if ($resu = $pdo->query($sql)) {

			if ($resu->fetchColumn() > 0) {

				// echo "alfkjadslkdfjsldkfjadlkfjdslkj";

				// The match already existed, so update database
				$STH = $pdo->prepare("UPDATE " . tournament::MATCHPLAYERTABELL . " SET
								  	  player_id = " . $this->avancemang . ",
									  order = " . $ordning . "
									  WHERE match_id = $theNewMatchID");
				try {
					$STH->execute();
				} 
				catch(PDOException $e) {
				    echo 'ERROR: ' . $e->getMessage();
				}


			}
			else {
				// It didn't exist, so insert it.

				$STH = $pdo->prepare("INSERT INTO " . tournament::MATCHPLAYERTABELL . " (match_id, player_id, order) VALUES ('$theNewMatchID', '$this->avancemang', '$ordning')");

				$STH->execute();

				// And create a new match in MATCHTABELL
				$STH = $pdo->prepare("
				INSERT INTO " . tournament::MATCHTABELL . " (id, tournamentDivision_id, sets) 
				VALUES ('$theNewMatchID', '" . tournament::$competitionid . "', 3)");

				$STH->execute();

				// And create three (or appropriate number) of sets in SETTABELL
				$STH = $pdo->prepare("
				INSERT INTO " . tournament::SETTABELL . " (match_id) 
				VALUES ('$theNewMatchID')");

				for ($j = 0 ; $j < $this->setsInMatch; $j ++ ) {
					$STH->execute();
				}	
			}
		}
	}


}

$klicket = new vinnarklick();

// send user back to the full tree.
   echo "<script type='text/javascript'>
   			document.location.href = '../treeclient.php';
		</script>";
   exit;     
 
?>
