<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  $oHTTPContext = new MHTTPContext();
  $oGame = new MGame();
  $oValidator = new MValidator();
  $oEntry = new MEntry();
  $oString = new MString();

  $sUserName = $oHTTPContext->getString("user");
  $sPassword = $oHTTPContext->getString("password");

  $statusCode = "";

  if($sUserName != null && $sPassword != null)
  {
    $oUser = new MUser();
    if($oUser->logIn($sUserName, $sPassword)){
      $iIDPlayer = $oHTTPContext->getInt("playerId");
      $iIDTeam = $oHTTPContext->getInt("teamId");
      if (($iIDTeam != null) && ($iIDPlayer == null))
      {
        $iIDPlayer = $iIDTeam;
      }
      $iIDGame = $oHTTPContext->getInt("gameId");
      $sScore = $oHTTPContext->getString("score");

      $iIDEntry = $oHTTPContext->getString("entryId");

      if($iIDEntry == null) {
        $div = $oGame->getDivision($iIDGame);
        if ($div != false){
          $entry = $oEntry->fromPlayerAndDivision($iIDPlayer, $div);
          $iIDEntry = (string)$entry->id;
          echo $iIDEntry;
        }
      }

      if($oEntry->isValidEntryID($iIDEntry)){
        $iScore = $oString->stripNonNumericChars($sScore);
        $scores = $oEntry->getScores2($iIDEntry, $iIDGame);
        $idScore = null;
        foreach($scores as $score){
          if (($score->score == null) || ($score->score == 0) || ($score->score == '0')){
            $idScore = $score->id;
            break;
          }
        } 

        if($idScore == null){
          $statusCode = "statusCode=4";            // already has score
        } else {
          if($oValidator->positiveInt($iScore)){
            $oEntry->updateScore($idScore, $iScore);
            $statusCode = "statusCode=0";          // entry score registred
          } else {
            echo "problem with score";
            $statusCode = "statusCode=2";          // score invalid
          }
        }
      } else {
        echo "Problem with entry";
        $statusCode = "statusCode=2";
      }
    } else {
      $statusCode = "statusCode=1";
    }
  } else {
    $statusCode = "statusCode=1";
  }
  echo $statusCode;
  $oHTTPContext->Log($statusCode);
?>
