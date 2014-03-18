<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  $oHTTPContext = new HTTPContext();

  $sUsername = $oHTTPContext->getString("user");
  $sPassword = $oHTTPContext->getString("password");

  if($sUsername != null && $sPassword != null)
  {
    $oUser = new User();
    if(!$oUser->logIn($sUsername, $sPassword)){
      echo "statusCode=1";
    } else {
      echo "statusCode=0";
    }
  } else {
    echo "statusCode=1";
  }

?>