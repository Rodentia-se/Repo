<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('SM 2013', __baseHref__);
  printTopper();

  $content = '<h2 class="entry-title">Admin tools</h2>';
  
  if (checkLogin($dbh, $ulogin)) {
    $currentPlayer = getCurrentPlayer($dbh, $ulogin);
    if ($currentPlayer->adminLevel > 0) {
      require_once(__ROOT__.'/functions/admin.php');
      $content .= getAdminMenu();
      $playerTables = getAdminPlayerTables($dbh, $ulogin);
      $datatables = getDataTables();
      switch ($_REQUEST['tool']) {
        case 'player':
          // $content .= '<br /><br />'.getAdminPasswordResetForm($dbh);
          $content .= $playerTables['players'];
        break;
        case 'user':
          $content .= $playerTables['users'];
        break;
        case 'payment':
          $content .= $playerTables['payments'];
        break;
        case 'results':
          $content .= $playerTables['results'];
        break;
        case 'volunteer':
          $volunteerTables = getAdminVolunteerTable($dbh);
          $content .= $volunteerTables['volunteers'];
          $content .= $volunteerTables['needs'];
          $content .= $volunteerTables['assignments'];
        break;
        case 'team':
//          $content .= getAdminTeamTable($dbh, true);
          $content .= getAdminTeamTable($dbh, false);
        break;
        case 'tshirt':
          $content .= getAdminTshirtTable($dbh, 'total');
          $content .= getAdminTshirtTable($dbh, 'buyers');
        break;
        case 'game':
          $content .= getAdminGameTable($dbh);
          $content .= getMachineById($dbh, 162)->getPrintInfo($dbh, 'div');
        break;
        case 'qualGroup':
          $content .= getAdminQualGroupTable($dbh);
          $content .= $playerTables['qualGroups'];
          $content .= '<p>'.$playerTables['csv'].'</p>';
        break;
        case 'score':
          $content .= getAdminScoreTable($dbh);
          $datatables = null;
        break;
      }
      $content .= $datatables;
    } else {
      $content .= 'You do not have access to this page';
    }
  }

  echo($content);
  printFooter($dbh, $ulogin);
  
?>
