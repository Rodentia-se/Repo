<?php
# class drawTree
include_once "tournament.php";


class drawTree {

	protected $rounds;
	private $rest; 				# No of competitors that "overflow" the beautiful tree (i.e. 2,4,8,16 etc).
	private $nodesFromStart;	# No of matches in first round.
	public $treehtml = "";
	public $matchID;
	

	public function __construct($sparris) {

		$this->setArray = $sparris;

		$this->calculateNoOfRounds();
		$this->calculateNoOfNodesFromStart();

		//echo "<p>NOC " . tournament::$numberOfCompetitors;
		// echo "<p>SIM: " . tournament::$numberOfSets;

		# Rita upp trädet	
		echo $this->setTree();

	}

	// Getters and setters

	public function getRounds() {
		return $this->rounds;
	}

	private function setRounds($r) {
		if (is_numeric($r))
			$this->rounds = $r;
	}

	private function calculateNoOfRounds() {

		$i = 1;
		$j = 0;

		while ($i <= tournament::$numberOfCompetitors) {
			$i = $i * 2;
			$j++;
		}
		
		$this->rest = (tournament::$numberOfCompetitors - ($i/2));# Check if there are any leftovers.

		if ($this->rest == 0)
			$j--;					# Decrease as it has moved one step too far when there's no rests.


		if (tournament::$creamfiles == 2) // Add one round if there are two creamfiles.
			$j++;

	
		$this->setRounds($j);
	}


	private function calculateNoOfNodesFromStart() {

		if ($this->rest == 0) {
	
			if (tournament::$creamfiles == 0)
				$this->nodesFromStart = tournament::$numberOfCompetitors / 2;

			if (tournament::$creamfiles == 2)
				$this->nodesFromStart = tournament::$numberOfCompetitors / (2 + tournament::$creamfiles);

		}
		else {

			if (tournament::$creamfiles == 0)
				$this->nodesFromStart = tournament::$numberOfCompetitors - pow(2, ($this->rounds-1));


		}		
	}



	private function setTree() {

		// Start with the "frames"
		$this->treehtml .= "<div id = 'brackets' class = 'brackets'>";
		$this->treehtml .= "<div id = 'b0' class = 'group" . ($this->rounds + 1) . "'>";

		# Initial values for the setArray 
		$match = 1;
		$playerA = 0;
		$playerB = 1;
		$runda = 1;

		if (tournament::$creamfiles == 0) {

			$incRunda = tournament::$numberOfCompetitors / pow(2, $runda);

		}

		else { // If there are creamfiles...

				$incRunda = $this->nodesFromStart;
				$cf_rundor = array("1a", "1b", "1c");
				$cf_roundCounter = 0;
				$runda = $cf_rundor[$cf_roundCounter];
			
		}



		// Draw the beginning of the round
		$this->treehtml .= "<div class = 'r" . $runda . "'>";

		for ($i = 1; $i < tournament::$numberOfCompetitors ; $i ++) {

			// Empty the A- and B-info.
			$aInfo = "";
			$bInfo = "";

			$inforuta = "<span class = 'info'><a href = 'class/matchInfo.php?matchid=" . $i . "'>" . $i . "</a>";
			if ($match < count($this->setArray['tagA'])) {

				$setvinsterA = 0;
				$setvinsterB = 0;

				if ($this->setArray['matchID'][$match] == $i) {

					if ($this->setArray['tagA'][$match] != '')
						$aInfo = $this->setArray['tagA'][$match];					

					if ($this->setArray['tagB'][$match] != '')
						$bInfo = $this->setArray['tagB'][$match];	
					
					for ($j = 0 ; $j < tournament::$numberOfSets ; $j ++ ) {
						$game = $this->setArray['abbr'][$match - 1];
						$inforuta .= " " . $game . " ";
						if ($j != 2)
							$inforuta .= "-";

						// Who won?
						if (!empty($this->setArray['vinnare'][$match-1])) {
							if ($this->setArray['vinnare'][$match-1] == $this->setArray['playerA'][$match-1])
								$setvinsterA ++;
							else
								$setvinsterB ++;
						} 
					
						$match ++;
					}
					$inforuta .= "</span>";

				
				}

			}

			if ($i == tournament::$numberOfCompetitors-1) {

				// Check if someone won the tournament.
				if (($this->setArray['tagA'][$match+1] != '') || ($this->setArray['tagB'][$match+1] != '')){
					if ($this->setArray['tagA'][$match+1] == '') 		
						$cinfo = $this->setArray['tagB'][$match+1] . " är mästare!";
					else
						$cinfo = $this->setArray['tagA'][$match+1] . " är mästare!";
				}
				else {
					$cinfo = "?";
				}
				
				// Rita upp finalstrecket
				$this->treehtml .= 	"</div>";
				$runda ++;
				$this->treehtml .= "<div class = 'r" . $runda . "'>";
				$this->treehtml .= "<div>
									  	<div class = 'bracketbox'>" . $inforuta . "
											<span class = 'teama'>" . $aInfo . "</span>
											<span class = 'teamaScore'>" . $setvinsterA . "</span>
											<span class = 'teamb'>" . $bInfo . "</span>
											<span class = 'teambScore'>" . $setvinsterB . "</span>
										</div>
				    			 </div>
							</div><div class = 'r" . ($runda + 1). "'>
									<div class = 'final'>
										<div class = 'bracketbox'>
											<span class = 'teamc'>" . $cinfo . "</span>
										</div>
									</div>
             	  				  </div>";
		
				$this->treehtml .= "</div></div>"; // Slut på bracket.
			}
			else {
				// Check if it's time to start a new round?
				if ($i == ($incRunda+1)) {
					$this->treehtml .= 	"</div>";

					#echo "<p>IR " . $incRunda . " Runda " . $runda . " CFRC " . $cf_roundCounter;

					if ((tournament::$creamfiles == 2) && ($cf_roundCounter < 2)) {
						$cf_roundCounter ++;
						$runda = $cf_rundor[$cf_roundCounter];
						$incRunda = $incRunda + $this->nodesFromStart;	
					}
					elseif ((tournament::$creamfiles == 2) && ($cf_roundCounter == 2)) {
						if ($runda == "1c")
							$runda = 2;
						else {
							$runda++;
						}
						$incRunda = $incRunda + ($this->nodesFromStart / pow(2, $runda-1));
						#echo "IR" . $incRunda;
					}
					else {
						$runda ++;
						$incRunda = tournament::$numberOfCompetitors / pow(2, $runda) + $incRunda;
						#echo "IR" . $incRunda;
					}

					$this->treehtml .= "<div class = 'r" . $runda . "'>";

				}

				$this->treehtml .= 	"<div>
									  	<div class = 'bracketbox'>" . $inforuta . "
											<span class = 'teama'>" . $aInfo . "</span>
											<span class = 'teamaScore'>" . $setvinsterA . "</span>
											<span class = 'teamb'>" . $bInfo . "</span>
											<span class = 'teambScore'>" . $setvinsterB . "</span>
										</div>
					    			 </div>";	
	
				$playerA += 2;
				$playerB += 2;
			}
		}
		

		return $this->treehtml;

	}

}
?>
