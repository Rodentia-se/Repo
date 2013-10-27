<?php
//class matchInfo.php
session_start();

include_once "tournament.php";
include "uppkoppling.php";


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

		echo "<p>NOC from tournament: " . $this->noc;

	}

	protected function getMatch() {

		// Skapa en kontakt med databasen
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT matchID, setID, playerA, playerB, spel, vinnare, SpA.Tag AS TagA, SpB.Tag AS TagB, SpA.Namn AS fullnamnA, SpB.Namn AS fullnamnB, Game.Abbreviation AS Abbr 
						  FROM " . tournament::SETTABELL . "
						  LEFT JOIN " . tournament::SPELARTABELL . " AS SpA ON sets.playerA = SpA.SpelarID
						  LEFT JOIN " . tournament::SPELARTABELL . " AS SpB ON sets.playerB = SpB.SpelarID
						  LEFT JOIN " . tournament::SPELTABELL . " AS Game ON sets.spel = Game.Gameid
						  WHERE matchID = $this->matchid 
						  ORDER BY matchID, setID");
		$STH->execute();

		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			$this->matchArray['matchID'][] = $row['matchID']; 
			$this->matchArray['setID'][] = $row['setID']; 
			$this->matchArray['playerA'][] = $row['playerA']; 
			$this->matchArray['playerB'][] = $row['playerB']; 
			$this->matchArray['spel'][] = $row['spel']; 
			$this->matchArray['tagA'][] = $row['TagA']; 
			$this->matchArray['tagB'][] = $row['TagB']; 
			$this->matchArray['fullnamnA'][] = $row['fullnamnA']; 
			$this->matchArray['fullnamnB'][] = $row['fullnamnB']; 
			$this->matchArray['vinnare'][] = $row['vinnare']; 
			$this->matchArray['abbr'][] = $row['Abbr'];
		
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



