<?php
require_once("../config/inc.config.php");
require_once(BASE_DIR . "classes/class.HTTPContext.php");
require_once(BASE_DIR . "classes/class.ArrayHelper.php");
require_once(BASE_DIR . "classes/class.Validator.php");
require_once(BASE_DIR . "classes/class.String.php");
require_once(BASE_DIR . "classes/class.LogFile.php");
require_once(BASE_DIR . "models/class.User.php");
require_once(BASE_DIR . "models/class.Game.php");
require_once(BASE_DIR . "models/class.Player.php");
require_once(BASE_DIR . "models/class.Entry.php");

	$oGame = new Game();
	$oPlayer = new Player();
	$oDivision = new Division();
	$oHTTPContext = new HTTPContext();
	$oArrayHelper = new ArrayHelper();
	$oValidator = new Validator();
	$oEntry = new Entry();
	$oString = new String();

	$sUsername = $oHTTPContext->getString("user");
	$sPassword = $oHTTPContext->getString("password");

	if($sUsername != null && $sPassword != null)
	{
		$oUser = new User();
		if($oUser->logIn($sUsername, $sPassword)){

			$iIDPlayer = $oHTTPContext->getInt("playerId");
			$iIDGame = $oHTTPContext->getInt("gameId");
			$sScore = $oHTTPContext->getString("score");
			$bVoid = $oHTTPContext->getString("void");

			$aPlayer = $oPlayer->getPlayer($iIDPlayer);
			$iYear = $aPlayer['player_year_entered'];
			$aIDPlayers = $oArrayHelper->assocToOrderedByKey($oPlayer->getPlayers($iYear), "id_player");

			$iIDEntry = $oHTTPContext->getInt("entryId");
			if($iIDEntry == null){
				$aEntries = $oEntry->getAllEntriesForPlayer($iIDPlayer);
				foreach($aEntries as $entry){
					$aEntryRounds = $oEntry->getRoundsInEntry($entry['id_entry']);
					foreach($aEntryRounds as $aEntryRound){
						if($aEntryRound['games_id_game'] == $iIDGame &&
							$aEntryRound['entry_round_score_game'] == 0){
							if(!$oEntry->isVoided($entry['id_entry'])){
								// we have a hit
								$iIDEntry = $entry['id_entry'];
							}
						}
					}
				}
			}

			if($oEntry->isValidEntryID($iIDEntry)){
				$aEntry = $oEntry->getEntryData($iIDEntry);
				$iIDDivision = $aEntry[0]['divisions_id_division'];
				$aDivision = $oDivision->getDivision($aEntry[0]['divisions_id_division']);
				$sDivision = $aDivision['division_name_short'];
	
				$aGames = $oGame->getAllGamesByYearAndDivision($iYear, $sDivision);
				$aIDsGame = $oArrayHelper->assocToOrderedByKey($aGames, "id_game");
					
				$aIDsGamesPosted = array();
				array_push($aIDsGamesPosted, $iIDGame);
	
	 			$iScore = $oString->stripNonNumericChars($sScore);
					
				if( $oValidator->uniqueValuesInArray($aIDsGamesPosted) &&
					$oValidator->validValuesInArray($aIDsGame, $aIDsGamesPosted) &&
					$oValidator->validValues($aIDPlayers, $iIDPlayer) &&
					$oEntry->getPlayerIDForEntry($iIDEntry) == $iIDPlayer){
	
					$aIDEntryRound = $oEntry->getEntryRound($iIDEntry, $iIDGame);
					if($oEntry->isVoided($iIDEntry)){
						echo "statusCode=3";						// already voided
					} else if($aIDEntryRound['entry_round_score_game'] != null &&
							$aIDEntryRound['entry_round_score_game'] > 0 ){
						echo "statusCode=4";						// already has score
					} else if(bVoid != null && $bVoid == "true"){
						$oEntry->voidEntry($iIDEntry, $bVoid);
						echo "statusCode=0";						// void ok!
					} else {
						if($oValidator->positiveInt($iScore)){
							$oEntry->updateEntryRound($aIDEntryRound['id_entry_round'], $iScore);
							echo "statusCode=0";					// entry round registred
						} else {
							echo "statusCode=2";					// score invalid
						}
					}
				} else {
					echo "statusCode=2";
				}
			} else {
				echo "statusCode=2";
			}
		} else {
			echo "statusCode=1";
		}
	} else {
		echo "statusCode=1";
	}
	
require_once(BASE_DIR . "includes/inc.end.php");
?>
