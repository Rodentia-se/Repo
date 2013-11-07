<?php
//class matchInfo.php
session_start();

include_once "tournament.php";
include "uppkoppling.php";
include "functions/general.php"; // EDIT!!! Sätt rättare sökväg till den här,
								 // eller vilken fil som behövs för admin-control.


class matchInfo {

	protected $matchid;
	public $noc;
	protected $matchArray;

	public function __construct($mid) {

		


		$mid = preg_replace("/[^0-9-]/","",$mid);
		if (is_numeric($mid))
			$this->matchid = $mid;

		$this->displayMatchData();

		$this->noc = $_SESSION['noc'];

		// echo "<p>NOC from tournament: " . $this->noc;

	}

	protected function getMatch() {

		// Skapa en kontakt med databasen
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT Match.id AS Matchid, 
									sets.id AS setid, 
									sets.machine_id, 
									sets.gameAcronym,
									mp1.initials AS TagA, 
									mp2.initials AS TagB,
									mp1.firstname AS FirstA,
									mp2.firstname AS FirstB,
									mp1.lastname AS LastA,
									mp2.lastname AS LastB,
									mp1.player_id AS pidA,
									mp2.player_id AS pidB,
									ms1.Score AS scoreA,
									ms2.Score AS scoreB 
								FROM " . tournament::MATCHTABELL . " AS Match 
								LEFT JOIN " . tournament::SETTABELL . " AS sets ON sets.match_id = Match.id

								LEFT JOIN (
    									SELECT * 
    									FROM " . tournament::MATCHPLAYERTABELL . " 
    									WHERE mp1.order = 1) mp1
											ON Match.id = mp1.match_id
								LEFT JOIN (
    									SELECT * 
    									FROM " . tournament::MATCHPLAYERTABELL . " 
    									WHERE mp2.order = 2) mp2
											ON Match.id = mp2.match_id
								LEFT JOIN (
										SELECT Score
										FROM " . tournament::MATCHSCORETABELL . "
										WHERE ms1.player_id = pidA) ms1
											ON ms1.matchSet_id = sets.id
								LEFT JOIN (
										SELECT Score
										FROM " . tournament::MATCHSCORETABELL . "
										WHERE ms2.player_id = pidB) ms2
											ON ms2.matchSet_id = sets.id
								WHERE Match.id = " . $this->matchid . "");
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			$this->matchArray['matchID'][] = $row['Matchid']; 
			$this->matchArray['setID'][] = $row['setid']; 
			$this->matchArray['playerA'][] = $row['pidA']; 
			$this->matchArray['playerB'][] = $row['pidB']; 
			$this->matchArray['spel'][] = $row['machine_id']; 
			$this->matchArray['tagA'][] = $row['TagA']; 
			$this->matchArray['tagB'][] = $row['TagB']; 
			$this->matchArray['fullnamnA'][] = $row['FirstA'] . " " . $row['LastA']; 
			$this->matchArray['fullnamnB'][] = $row['FirstB'] . " " . $row['LastB']; 
			if ($row['scoreA'] > $row['scoreB'])
				$this->setArray['vinnare'][] = $row['pidA'];
			else 
				$this->setArray['vinnare'][] = $row['pidB'];
			$this->matchArray['abbr'][] = $row['gameAcronym'];
		
		}

	}



	protected function displayMatchData() {

		// Collect info 'bout the match
		$this->getMatch();

		// Display the players
		echo "<div><div class = 'tagruta' id = 'SpA'><h1>" . $this->matchArray['tagA'][0] . "</h1></div>";
		echo "<div class = 'tagruta' id = 'spel'><h1>Match " . $this->matchid . "</h1></div>";
		echo "<div class = 'tagruta' id = 'SpB'><h1>" . $this->matchArray['tagB'][0] . "</h1></div></div>";

		echo "<div class = 'clear'></div>";

		echo "<div class = 'fullinfo'><p>" . $this->matchArray['fullnamnA'][0] . "</p></div>";
		echo "<div class = 'fullinfo'><p>Spel</p></div>";
		echo "<div class = 'fullinfo'><p>" . $this->matchArray['fullnamnB'][0] . "</p></div>";

		// Get the players ID-numbers and tags more comfortable
		$playerA = $this->matchArray['playerA'][0];
		$playerB = $this->matchArray['playerB'][0];
		$tagA = $this->matchArray['tagA'][0];
		$tagB = $this->matchArray['tagB'][0];
	
		// variables for number of set wins
		$a = 0;
		$b = 0;

		for ($i = 0 ; $i < tournament::$numberOfSets ; $i ++ ) {

			$vinnarbuttonA = "";
			$vinnarbuttonB = "";

			if (empty($this->matchArray['vinnare'][$i])) {

				// Ska man lägga till en första skydd för felklick? Typ med en alert som nedan?
				// onClick="return confirm('Är du helt säker på resultatet?') 
	
				$vinnarbuttonA = "<button id = 'set" . $this->matchArray['setID'][$i] . "sid" . $playerA . "'>" . $tagA . "</button>";
				$vinnarbuttonB = "<button id = 'set" . $this->matchArray['setID'][$i] . "sid" . $playerB . "'>" . $tagB . "</button>";
			}
			else {
				if ($this->matchArray['vinnare'][$i] == $playerA) {
					$vinnarbuttonA = $tagA;
					$a ++;
				}
				else {
					$vinnarbuttonB = $tagB;
					$b ++;
				}
			}	

			if (($i == (tournament::$numberOfSets-1)) && ((($a == 2) && ($b == 0)) || (($b == 2) && ($a == 0)))) {
				$vinnarbuttonA = "";
				$vinnarbuttonB = "";
			}			


			// Put admincontrol here (AND MAKE IT WORK)
			$player = getCurrentPlayer($dbh); 
			if ($player->adminLevel == 0) { 

				// If user is not Admin, user shall have no buttons to click.
				$vinnarbuttonA = "";
				$vinnarbuttonB = "";
			}

			echo "<div class = 'set'>";
			echo "<div class = 'winA'>" . $vinnarbuttonA . "</div>";
			echo "<div class = 'game'>" . $this->matchArray['abbr'][$i] . "</div>";
			echo "<div class = 'winB'>" . $vinnarbuttonB . "</div>";
			echo "</div>";

		}
		echo "<div style = 'clear:both;' id = 'klickresultat'></div>";
	}		

}




if (isset($_GET['matchid'])) {

	echo "<html><head><link rel = 'stylesheet' type = 'text/css' href = '../css/mi.css'>";
	echo "<script src='http://code.jquery.com/jquery-1.10.1.min.js'></script></head>";
	echo "<body>";
	$matchen = new matchInfo($_GET['matchid']);
}

?>


<script type = "text/javascript">
 $.ajaxSetup({
         cache: false
    }); 
	$(document).ready(function(){
        $("button").click(function() {
			var idt = $( this ).attr('id');
	        $("#klickresultat").load("vinnarklick.php?button=" +idt);
        });
    });

</script>



