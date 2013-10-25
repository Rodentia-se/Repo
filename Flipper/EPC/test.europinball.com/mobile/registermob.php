<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  $oHTTPContext = new HTTPContext();
  $oGame = new Game();
  $oValidator = new Validator();
  $oEntry = new Entry();
  $oString = new String();

  $sUserName = $oHTTPContext->getString("user");
  $sPassword = $oHTTPContext->getString("password");

  if($sUserName != null && $sPassword != null)
  {
    $oUser = new User();
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
        }
      }

      if($oEntry->isValidEntryID($iIDEntry)){
        $iScore = $oString->stripNonNumericChars($sScore);
        $scores = $oEntry->getScores($iIDEntry, $iIDGame);
        $idScore = null;
        foreach($scores as $score){
          if (($score->score == null) || ($score->score == 0) || ($score->score == '0')){
            $idScore = $score->id;
            break;
          }
        } 

        if($idScore == null){
          echo "statusCode=4";            // already has score
        } else {
          if($oValidator->positiveInt($iScore)){
            $oEntry->updateScore($idScore, $iScore);
            echo "statusCode=0";          // entry score registred
          } else {
            echo "statusCode=2";          // score invalid
          }
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
?>