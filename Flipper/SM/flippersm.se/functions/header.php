<?php
  function printHeader($title = 'EPC', $baseHref = "/") {
    echo('
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/jquery.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/jquery-ui.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/jquery.jeditable.mini.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/jquery.dataTables.editable.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/purl.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/recaptcha_ajax.js"></script>
    <script type="text/javascript" src="'.$baseHref.'/js/general.js"></script>
    <link href="'.$baseHref.'/css/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css" />
    <link href="'.$baseHref.'/css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="'.$baseHref.'/css/epc.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="'.$baseHref.'/js/contrib/ga.js"></script>
    <link rel="shortcut icon" href="'.__baseHref__.'/images/favicon.ico" type="image/x-icon" />
    <title>'.$title.'</title>
  </head>
    ');
  }
  
  function printTopper($onLoad = null) {
    echo('
  <body onload="'.$onLoad.'">
    <div id="page">
    ');
  }

/*
      <div id="topper">
        <div id="topperBottom"></div>
        <div id="topperLeft"></div>
        <div id="topperRight"></div>
      </div>
  */
    
  function printFooter($dbh, $ulogin) {
    echo('</div><div id="loggedInOut">');
    if (checkLogin($dbh, $ulogin, false)) {
      $player = getPlayerById($dbh, getIdFromUser($dbh, $ulogin->Username($_SESSION['uid'])));
      echo('<p class="italic">You are logged in as '.$player->firstName.' '.$player->lastName.'</p>');
    } else {
      echo('<p class="italic">You are not logged in.</p>');
    }
    echo('
      </div>
      <div id="debug" align="left"></div>
      <div id="debug2"></div>
    ');
/*
    </div>
  </body>
</html>
*/
  }
  //     <script type="text/javascript" src="'.$scriptPath.'json.js"></script>

?>