<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  $oHTTPContext = new MHTTPContext();

  $sUsername = $oHTTPContext->getString("user");
  $sPassword = $oHTTPContext->getString("password");

  if($sUsername != null && $sPassword != null)
  {
    $oUser = new MUser();
    if(!$oUser->logIn($sUsername, $sPassword)){
      echo "statusCode=1";
    } else {
      $adminLevel = $oUser->getAdminLevel();
      echo "statusCode=0"."&adminLevel=".$adminLevel;
    }
  } else {
    echo "statusCode=1";
  }

?>