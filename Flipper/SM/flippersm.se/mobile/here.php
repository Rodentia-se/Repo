<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  $oHTTPContext = new MHTTPContext();

  $sUserName = $oHTTPContext->getString("user");
  $sPassword = $oHTTPContext->getString("password");

  if($sUserName != null && $sPassword != null)
  {
    $oUser = new MUser();
    if($oUser->logIn($sUserName, $sPassword))
    {

      $playerId = $oHTTPContext->getString("playerId");
      $here = $oHTTPContext->getString("here");

      $player = new MPlayer();

      $type = 'qual';
      if ($here)
      {
        $type = $here;
      }

      $result = $player->setHere($playerId, $type);
      if ($result) {
        echo "statusCode=0";
      } else {
        echo "statusCode=1";
      }
    }
  }
  else
  {
    echo "statusCode=1"
  }
?>