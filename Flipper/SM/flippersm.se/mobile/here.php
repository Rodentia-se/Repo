<?php
  require_once('../functions/general.php');
  require_once('mobile.php');

  $oHTTPContext = new HTTPContext();

  $here = $oHTTPContext->getString("here");

  if ($here == 1)
    echo "statusCode=0";
  } else {
    echo "statusCode=1";
  }

?>