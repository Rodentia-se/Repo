<?php
  require_once('functions/general.php');
  require_once('functions/header.php');

  printHeader('EPC 2013', __baseHref__);
  
	switch(($_REQUEST['obj']) ? $_REQUEST['obj'] : array_slice(preg_split('/\//', preg_split('/\?/', $_SERVER['REQUEST_URI'])[0]), -2)[0]) {
		case 'country':
		case 'countries':
      $type = 'country';
    break;
		case 'national-teams':
		case 'national-team':
      $national = true;
      $type = 'team';
    break;
    case 'team':
		case 'teams':
		case 'your-team':
      $national = false;
      $type = 'team';
    break;
		case 'city':
		case 'cities':
      $type = 'city';
    break;
		case 'region':
		case 'regions':
      $type = 'region';
    break;
		case 'continent':
		case 'continents':
      $type = 'continent';
    break;
		case 'manufacturer':
		case 'manufacturers':
      $type = 'manufacturer';
    break;
		case 'machine':
		case 'machines':
		case 'game':
		case 'games':
      $type = 'game';
    break;
		case 'gender':
		case 'genders':
      $type = 'gender';
    break;
    default:
      $type = 'player';
    break;
	}
  echo $obj;
  $id = ($_REQUEST['id']) ? $_REQUEST['id'] : null;
  $table = true;

  if ($type == 'player' && $id == 'self') {
    if (checkLogin($dbh, $ulogin, true)) {
      $id = getIdFromUser($dbh, $_SESSION['username']);
    }
    $table = false;
  }

  if ($type == 'team' && $id == 'self') {
    if (checkLogin($dbh, $ulogin, true)) {
      $player = getPlayerById($dbh, getIdFromUser($dbh, $ulogin->Username($_SESSION['uid'])));
      $team = $player->getTeam($dbh);
      if ($team) {
        $id = $team->id;
      } else {
        $content = '<div class="infoDiv">You are not a member of any team. You can <a href="'.__baseHref__.'/registration/team-registration/">go here</a> to register a team, or you can ask a member of an already existing team to add you to his/her team.</div>';
      }
    }
    $table = false;
  }

  if (preg_match('/^[0-9]+$/', $id)) {
    $content = getInfo($dbh, $type, $id);
    if (!$content) {
      $content = '<div class="infoDiv">No '.$type.' with ID '.$id.' found!</div>';
      $table = false;
    }
    if ($type == 'player' || $type == 'game' || $type == 'team') {
      $table = false;
    }
  }

  if ($table) {
    if ($type == 'team') {
      printTopper('getObjects(\'teams\')');
      $content .= getTable('team', $national);
    } else {
      printTopper(($type == 'manufacturer' || $type == 'game') ? 'getObjects(\'games\');' : 'getObjects();');
      $content .= getTable(($type == 'manufacturer' || $type == 'game') ? 'game' : 'player');
    }
  } else {
    printTopper();    
  }

  
  echo($content);
  printFooter($dbh, $ulogin);

?>