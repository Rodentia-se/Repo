<?php
//class matchinfo.php

class matchInfo extends tournament {

	protected $matchid = 0;

	public function __construct($mid) {

		$mid =  preg_replace("/[^0-9-]/","",$mid);
		if (is_numeric($mid))
			$this->matchid = $mid;

		$this->displayMatchData();

	}


	protected function displayMatchData() {

		$this->getMatch();

		// Display the players
		echo "<div class = 'tag'>" . $this->matchArray['tagA'][0] . "<div class = 'fullname'>" . $this->matchArray['fullnamnA'][0];
		echo "<div class = 'tag'>" . $this->matchArray['tagB'][0] . "<div class = 'fullname'>" . $this->matchArray['fullnamnB'][0];

		// Get the players ID-numbers and tags more comfortable
		$playerA = $this->matchArray['playerA'][0];
		$playerB = $this->matchArray['playerB'][0];
		$tagA = $this->matchArray['tagA'][0];
		$tagB = $this->matchArray['tagB'][0];
	
		for ($i = 0 ; $i < $this->numberOfSets ; $i ++ ) {

			if (empty($this->matchArray['vinnare'][$i])) {
				$vinnarbuttonA = "<input type = 'button' name = '" . $playerA . "[]' value = '" . $tagA . "'>";
				$vinnarbuttonB = "<input type = 'button' name = '" . $playerB . "[]' value = '" . $tagB . "'>";
			}
			else {
				if ($this->matchArray['vinnare'][$i] == $playerA)
					$vinnarbuttonA = $tagA;
				else 
					$vinnarbuttonB = $tagB;
			}

			echo "<div>" . $vinnarbuttonA . "<div class = 'game'>" . $this->matchArray['abbr'][$i] . "<div>" . $vinnarbuttonB;
			

		}
	}		

}

$matchen = new matchInfo($_GET['matchid']);


?>
