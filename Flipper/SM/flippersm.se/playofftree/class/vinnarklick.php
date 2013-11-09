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

		$STH = $pdo->prepare("UPDATE " . tournament::SETTABELL . " SET
							  vinnare = {$this->sid[1]} 
							  WHERE setID = {$this->set[1]}");

		$STH->execute();

	}


	protected function gotAWinner() {

		// Establish connection with the database
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		// Get winners from each set that belongs to the newly updated match.
		$STH = $pdo->prepare("SELECT " . tournament::SETTABELL . ".vinnare, matchID FROM " . tournament::SETTABELL . " 
							  WHERE " . tournament::SETTABELL . ".matchID IN 
							  (SELECT matchID FROM " . tournament::SETTABELL . " WHERE setID = {$this->set[1]})");
		$STH->execute();
		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			$vinnare[] = $row['vinnare'];
			$this->matchid = $row['matchID'];
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
			echo "BULTTUUTAN";
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
				$whichplayer = "playerA";
			else 
				$whichplayer = "playerB";

		}

		// Check if it exists.
		$connect = new uppkoppling();
		$pdo = $connect->conn();
		$sql = "SELECT COUNT(*) FROM " . tournament::SETTABELL . " WHERE matchID = $theNewMatchID"; 
		if ($resu = $pdo->query($sql)) {

			if ($resu->fetchColumn() > 0) {

				echo "alfkjadslkdfjsldkfjadlkfjdslkj";

				// The match already existed, so update database
				$STH = $pdo->prepare("UPDATE " . tournament::SETTABELL . " SET
								  	  $whichplayer = $this->avancemang 
									  WHERE matchID = $theNewMatchID");
				try {
					$STH->execute();
				} 
				catch(PDOException $e) {
				    echo 'ERROR: ' . $e->getMessage();
				}
			}
			else {
				// It didn't exist, so insert it.

				$STH = $pdo->prepare("INSERT INTO " . tournament::SETTABELL . " (matchID, competitionID, " . $whichplayer . ") VALUES ('$theNewMatchID', '$this->competitionid', '$this->avancemang')");

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
   			document.location.href = '../playoffclassic.php';
		</script>";
   exit;     
 
?>
