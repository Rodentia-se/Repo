<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  
  $content = '
    <h2 class="entry-title">Live standings</h2>
    <p>Individual player and machine standing can be viewed by clicking the links in the tables below.</p>
    <ul>
      <li>The table is now sorted to as much detail as it can be done, but for technical reasons the sorting is wrong in some insignificant places.</li>
      <li>With the same points, place is determined by the best top place on an individual machines.</li>
      <li>If all top individual machine places are identical, place is determined by order on any mutual machines.</li>
      <li>If all else is equal, we will draw lots from a famous hat.</li>
      <li>No tie-breaker matches will be needed for the main tournament:</li>
      <ul>
        <li>Significant place <b>16</b>: Single player place.</li>
        <li>Significant place <b>32</b>: Single player place.</li>
        <li>Significant place <b>64</b>: <b>Jochen Ludwig</b> wins with his higher top individual machine place: #2.</li>
        <li>Significant place <b>128</b>: Single player place.</li>
      </ul>
      <li>Insignificant place orders that are incorrectly listed in the table:</li>
      <ul>
        <li>12 Solymosi, 13 Trepp, 14 Bodin, 15 Bocquet</li>
        <li>75 Holmsten, 76 van Schooneveld, 77 Enekull</li>
        <li>102 Patrakka, 103 Bergh</li>
        <li>107 Mortensen, 108 Hydén, 109 Birgersson</li>
        <li>147 Lundin, 148 Lunden, 149 Kaczmarek</li>
        <li>151 Korrodi, 152 Crisantemi</li>
        <li>170 Hansson, 171 Johannessen</li>
        <li>177 Thronström, 178 Haverinen, 179 Scheja</li>
      </ul>
    </ul>
    '; 
  

  $players = getPlayers($dbh, ' where m.tournamentEdition_id = 1');
  $content .= '
          <div id="tabs">
          <ul>
            <li class="tabs"><a href="#mainTable"><h2>Main tournament</h2></a></li>
            <li class="tabs next"><a href="#classicsTable"><h2>Classics tournament</h2></a></li>
            <li class="tabs next"><a href="#natTeamTable"><h2>National teams</h2></a></li>
            <li class="tabs next"><a href="#teamTable"><h2>Other teams</h2></a></li>
          </ul>
          <div id="mainTable">
            <table class="mainStandings">
              <thead>
                <tr>
                  <th>Place</th>
                  <th>Name</th>
                  <th>Games</th>
                  <th>Points</th>
                  <th>Here</th>
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
                  <td><input type="checkbox" '.(($player->hereFinal) ? 'checked' : '').' disabled></td>
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
  $teams = getTeams($dbh, false, true);
  $content .= '
          <div id="natTeamTable"> 
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
  foreach ($teams as $team) {
    $qualEntries = $team->getAllEntries($dbh, ' group by qe.id', 12);
    if ($qualEntries) {
      foreach ($qualEntries as $qualEntry) {
        $content .= '
                <tr>
                  <td>'.$qualEntry->entryPlace.'</td>
                  <td>'.$team->getLink().'</td>
                  <td>
        ';
      }
      $qualScores = $team->getAllEntries($dbh,  ' group by qs.machine_id', 12);
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
  $teams = getTeams($dbh);
  $content .= '
          <div id="teamTable"> 
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
  foreach ($teams as $team) {
    $qualEntries = $team->getAllEntries($dbh, ' group by qe.id', 3);
    if ($qualEntries) {
      foreach ($qualEntries as $qualEntry) {
        $content .= '
                <tr>
                  <td>'.$qualEntry->entryPlace.'</td>
                  <td>'.$team->getLink().'</td>
                  <td>
        ';
      }
      $qualScores = $team->getAllEntries($dbh,  ' group by qs.machine_id', 3);
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
  $content .=  '</tbody></table></div></div>';
  $content .= getDataTables('.standings');
  $content .= getDataTables('.mainStandings');

  echo $content;

  printFooter($dbh, $ulogin);
?>
