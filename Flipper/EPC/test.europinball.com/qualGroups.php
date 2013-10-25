<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  
  $choice = false;

  $id = (isset($_REQUEST['id']) && preg_match('/^[0-9]+$/', $_REQUEST['id'])) ? $_REQUEST['id'] : false;
  
  if ($id) {
    $qualGroup = getQualGroupById($dbh, $id);
  }
  
  if ($qualGroup) { 
    
    printTopper('getObjects();');
    $content = getInfo($dbh, 'qualGroup', $id);
    $content .= getTable('player');
    
  } else {
  
    printTopper();
    $qualLimit['main'] = __mainQualLimit__;
    $qualLimit['classics'] = __classicsQualLimit__;
    $qualLimit['main'] = 60;
    $qualLimit['classics'] = 30;
  
    $content = '<h2 class="entry-title">Qualification group status</h2>';
    if ($choice) {
      $content .= '<p>Below you can find the current amount of players in each qualification group. After each time slot you will find two numbers - the number of players that have chosen to be available for the time slot, and after the slash is the number of players that have chosen that slot as their prefered slot.</p>'; 
      $content .= '<p>The maximum number of players depend on the final number of games in the tournament, but it will end up at somewhere around 50. <b>As long as the number after the slash is below '.$qualLimit['main'].' ('.$qualLimit['classics'].' in Classics), everyone will be able to play at their prefered time slots.</b></p>';
    } else {
      $content .= '<p>Below are all the qualification groups, and the amount of players in each.</p>';
    }
    $qualGroups = getQualGroups($dbh);
  
    $player = getCurrentPlayer($dbh, $ulogin);
  
    if ($player) {
      $content .= '<p>You are assigned to the groups in <span="bold">bold font</span>.</p>';
      $playerQualGroups = $player->getQualGroups($dbh, 1, false);
      $playerPreferedQualGroups = $player->getQualGroups($dbh, 1, true);
    }
  
    if ($playerQualGroups) {
      $content .= ($choice) ? '<p>Your chosen time slots are marked with <span class="yellow">yellow background</span>.</p>' : '';
      foreach($playerQualGroups as $playerQualGroup) {
        $playerQualGroupIds[] = $playerQualGroup->id;
      }
    }
  
    if ($playerPreferedQualGroups) {
      $content .= ($choice) ? '<p>Your prefered time slots are marked with a <span class="yellow bold">bold font on yellow background</span>.</p>' : '';
      foreach($playerPreferedQualGroups as $playerPreferedQualGroup) {
        $playerPreferedQualGroupIds[] = $playerPreferedQualGroup->id;
      }
    }
  
    $content .= ($choice) ? '<p>Overcrowded time slots are marked with a <span class="red">red font</span>.</p><p>If a time slot is overcrowded, we will need to move people to other time slots. People will be granted their prefered time slot in order of participation fee payment date until the time slot is full.</p><br />' : '';
  
    if($qualGroups && count($qualGroups > 0)) {
    
      $tournamentDivisionIds = [];
      foreach($qualGroups as $qualGroup) {
        if (!in_array($qualGroup->tournamentDivision_id, $tournamentDivisionIds)) {
          $tournamentDivisionIds[] = $qualGroup->tournamentDivision_id;
          $qualGroupsByDiv[$qualGroup->tournamentDivision_id] = [];
        }
        array_push($qualGroupsByDiv[$qualGroup->tournamentDivision_id], $qualGroup);    
      }

      foreach($tournamentDivisionIds as $tournamentDivisionId) {
        $type = ($tournamentDivisionId == 1) ? 'main' : 'classics';
        foreach($qualGroupsByDiv[$tournamentDivisionId] as $qualGroup) {
          if (!($date) || $date != $qualGroup->date) {
            if ($date) {
              $content .= '</table>';
            }
            $date = $qualGroup->date;
            $content .= '
              <table id="qualGroupMain'.$date.'Table" class="qualGroupTable">
            <tr>
            <td><h2><b>'.$date.' ('.ucfirst($type).')</b></h2></td>
            </tr>
            ';
          }
          $content .= '<tr><td';
          if ($choice) {
            $content .= ' class="';
            if ($player) {
              $content .= ($playerQualGroups && in_array($qualGroup->id, $playerQualGroupIds)) ? ' yellow' : '';
              $content .= ($playerPreferedQualGroups && in_array($qualGroup->id, $playerPreferedQualGroupIds)) ? ' bold' : '';          
            }
            $content .= ($qualGroup->getNoOfPlayers($dbh, true) > $qualLimit[$type]) ? ' red">' : '">';
            $content .= ucfirst($qualGroup->name).': '.$qualGroup->getNoOfPlayers($dbh).' / '.$qualGroup->getNoOfPlayers($dbh, true);
          } else {
            $content .= ($qualGroup->getNoOfPlayers($dbh, true) > $qualLimit[$type]) ? ' red' : '';
            $content .= ($player && ($player->mainQualGroup_id == $qualGroup->id || $player->classicsQualGroup_id == $qualGroup->id)) ? ' class="bold">' : '>';
            $content .= '<a href="'.$qualGroup->getLink(false).'">'.ucfirst($qualGroup->name).'</a>: '.$qualGroup->getNoOfAssignedPlayers($dbh);
          }
          $content .= '</td></tr>';
        }
      } 
      $content .= '
        </table>
        <br /><p style="clear: left;">The team tournament qualification is scheduled for <span class="big">Saturday 2013-09-14 1300-1600</span> and finals at <span class="big">1600-1900</span>.</p>
      ';
      if ($player) {
        $team = $player->getTeam($dbh);
        if ($team) {
          $content .= '<p>You are a member of the team "'.$team->getLink().'".</p>';
        }
      }
      $players = getPlayers($dbh, ' where m.tournamentEdition_id = 1');
      $content .= '
        <div id="tabs">
          <ul>
            <li class="tabs"><a href="#mainTable"><h2>Main tournament</h2></a></li>
            <li class="tabs next"><a href="#classicsTable"><h2>Classics tournament</h2></a></li>
          </ul>
          <div id="mainTable">
            <table class="standings">
              <thead>
                <tr>
                  <th>Place</th>
                  <th>Name</th>
                  <th>Games</th>
                  <th>Points</th>
                </tr>
              </thead>
              <tbody>
      ';
      foreach ($players as $player) {
        $qualEntries = $player->getAllEntries($dbh, ' group by qe.id', 1);
        foreach ($qualEntries as $qualEntry) {
          $content .= '
                <tr>
                  <td>'.$qualEntry->entryPlace.'</td>
                  <td>'.$player->getLink().'</td>
                  <td>
          ';
          $qualScores = $player->getAllEntries($dbh,  ' group by qs.machine_id', 1);
          foreach ($qualScores as $qualScore) {
            $content .= (($qualScore->bestPlace) ? $qualScore->bestPlace.' on ' : '').'<a href="'.__baseHref__.'/game/?obj=game&id='.$qualScore->gameId.'">'.$qualScore->game.'</a>'.(($qualScore->maxPoints) ? ' ('.$qualScore->maxPoints.')' : '').', ';
          }
          $content = preg_replace('/, $/', '', $content).'
                  </td>
                  <td>'.$qualScore->entryPoints.'</td>
                </tr>
          ';
        }
      }
      $content .=  '</tbody></table></div>';
      $players = getPlayers($dbh, ' where cl.id is not null and m.tournamentEdition_id = 1');
      $content .= '
          <div id="classicsTable"> 
            <table class="standings">
              <thead>
                <tr>
                  <th>Place</th>
                  <th>Name</th>
                  <th>Games</th>
                  <th>Points</th>
                </tr>
              </thead>
              <tbody>
      ';
      foreach ($players as $player) {
        $qualEntries = $player->getAllEntries($dbh, ' group by qe.id', 2);
        foreach ($qualEntries as $qualEntry) {
          $content .= '
                <tr>
                  <td>'.$qualEntry->entryPlace.'</td>
                  <td>'.$player->getLink().'</td>
                  <td>
          ';
          $qualScores = $player->getAllEntries($dbh,  ' group by qs.machine_id', 2);
          foreach ($qualScores as $qualScore) {
            $content .= (($qualScore->bestPlace) ? $qualScore->bestPlace.' on ' : '').$qualScore->game.(($qualScore->maxPoints) ? ' ('.$qualScore->maxPoints.')' : '').', ';
          }
          $content = preg_replace('/, $/', '', $content).'
                  </td>
                  <td>'.$qualScore->entryPoints.'</td>
              </tr>
          ';
        }
      }
      $content .=  '</tbody></table></div></div>';
      $content .= getDataTables('.standings');

     /*
      $content .= '
        <table id="scores">
          <thead>
            <tr>
              <th>Place</th>
              <th>Name</th>
              <th>Game</th>
              <th>Score</th>
              <th>Points</th>
            </tr>
          </thead>
          <tbody>
      ';
      foreach($players as $player) {
        $qualScores = $player->getAllEntries($dbh);
          $content .= '
            <tr>
              <td>'.$qualScore->place.'</td>
              <td>'.$player->getLink().'</td>
              <td>'.$qualScore->game.'</td>
              <td>'.$qualScore->score.'</td>
              <td>'.$qualScore->points.'</td>
            </tr>
          ';
        }
      }
      $content .= '
          |</tbody>
        </table>
      ';
      $content .= getDataTables('#scores');
      */
/*      foreach ($players as $player) {
          echo('<p>'.$player->name.': ');
         $qualScores = $player->getAllEntries($dbh, ' group by qs.machine_id');
         foreach ($qualScores as $qualScore) {
          echo($qualScore->game.', ');
         }
         echo '<br />';
       }
       */
      } else {
      $content .= 'Something must have gone wrong, because I can\'t find any qualification groups in the database?';
    }
  } 
  echo $content;

  printFooter($dbh, $ulogin);
?>
