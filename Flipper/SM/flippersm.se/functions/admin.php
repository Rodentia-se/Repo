<?php

  $currentPlayer = getCurrentPlayer($dbh, $ulogin);
  if ($currentPlayer->adminLevel > 0) {
    
    function getAdminMenu() {
      return '
        <ul>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=player">Player tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=user">User tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=payment">Payment tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=results">Results tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=qualGroup">Qualification group tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=team">Team tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=volunteer">Volunteer tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=tshirt">T-shirt tools</a></li>
          <li><a href="'.__baseHref__.'/adminTools.php?tool=game">Game tools</a></li>
          <li><a href="#" onclick="deNorm(this);" id="deNorm">Denormalization</a><span class="error errorSpan toolTip" id="deNormSpan"></span></li>
        </ul>
      ';
    }

    function getAdminChangeForm($dbh, $objs = false, $type = false) {
      $objs = ($objs) ? $objs : getObjectList($dbh, 'player', array ('tournament' => '1'));
      return '
        <label>Change '.$type.' for player: </label>
        '.createSelect($objs, $type.'Player', 0, $type.'Player').'
        <label>Total '.$type.': </label>
        <input type="text" value="" id="'.$type.'New">
        <input type="button" value="Change" onclick="'.$type.'Change(this);">
        <span id="'.$type.'Span" class="errorSpan">Reload page to update table</span><br /><br />
      ';
    }
  
    function getAdminPlayerTables($dbh, $ulogin, $tableType = false) {
      $types = ($tableType) ? array($tableType) : array('payments', 'users', 'players', 'qualGroups', 'results');
      
      $meta = array(
        'payments' => array(
          'header' => '
            <br /><br /><h2 class="entry-title">Payments</h2>
            <ul>
              <li>ID: Person ID</li>
              <li>M-ID: Player ID for main tournament</li>
              <li>C-ID: Player ID for Classics</li>
              <li>Team: The ID of the player\'s team</li>
              <li>T-shirts: The number of T-shirts pre-ordered by the player</li>
              <li>Total: The amount of SEK the player is supposed to pay in total</li>
              <li>Paid: The amount in SEK currently paid by the player.
                <ul>
                  <li>This is the total - if the player has already paid 200 and give you 200 more, change this to 400.</li>
                </ul>
              </li>
              <li>DIff: The amount still to be paid. If it\'s a negative number, the player has paid too much.</li>
              <li>Payment time: The date and time the player made his/her first payment, and thus used for qualification group assignment order.</li>
            </ul>
          ',
          'cols' => array('player', 'id', 'main', 'classics', 'team', 'tshirts', 'total', 'paid', 'diff', 'payDate')
        ),
        'players' => array(
          'header' => '
            <br /><br /><h2 class="entry-title">Players</h2>
            <ul>
              <li>ID: Person ID</li>
              <li>M-ID: Player ID for main tournament</li>
              <li>C-ID: Player ID for Classics</li>
              <li>'.getCurrentPlayer($dbh, $ulogin)->getQR(false).': Print player QR code</li>
              <li>Q: Mark player as present for qualification play.</li>
              <li>F: Mark player as present for finals play</li>
              <li>V: Mark player as present for voluntary work</li>
              <li><img src="'.__baseHref__.'/images/arrive.png" class="icon" alt="Nyanländ" title="Klicka här för checklistan för nyanlända">: Go to player arrival checklist</li>
            </ul>
          ',
          'cols' => array('player', 'id', 'main', 'classics', 'ifpa', 'mailAddress', 'mobileNumber', 'here')
        ),
        'users' => array(
          'header' => '
            <br /><br /><h2 class="entry-title">User (players)</h2>
            <ul>
              <li>ID: Person ID</li>
              <li>M-ID: Player ID for main tournament</li>
              <li>UID: The player\'s user ID</li>
              <li>User: The player\'s username</li>
              <li>Admin = "No" = adminLevel 0</li>
              <li>Admin = "Admin" = adminLevel 1</li>
              <li>Admin = "Scorekeeper" = adminLevel 2</li>
            </ul>
          ',
          'cols' => array('player', 'id', 'main', 'mailAddress', 'mobileNumber', 'uid', 'username', 'password', 'admin')
        ),
        'results' => array(
          'header' => '
            <br /><br /><h2 class="entry-title">Player results</h2>
            <ul>
              <li>ID: Person ID</li>
              <li>M-ID: Player ID for main tournament</li>
              <li>C-ID: Player ID for Classics</li>
              <li>Place: This is the final place in the tournament. Four players on a tied 5th place should all get 5 here.
              <li>WPPR: This is the place reported to IFPA. Four players on a tied 5th place should get 6 here.
              <ul>
                <li class="italic">You only need to change WPPR place if it\'s a tie, and thus different from ordinary place</li>
              </ul>
            </ul>
          ',
          'cols' => array('player', 'id', 'main', 'classics', 'place', 'classicsPlace')
        ),
        'qualGroups' => array(
          'header' => '<br /><br />
            <h2 class="entry-title">Players</h2>
            <ul class="italic">
              <li><span class="errorTd">Chosen</span> = player is in team, and prefered qualification group is during the team tournament.</li>
              <li><span class="errorTd">Assigned</span> = player has been assigned both main and classics at the same time slot.</li>
              <li><span class="green">Assigned</span> = player has been assigned preferred time slot.</li>
              <li><span class="yellow">Assigned</span> = player has been assigned one of the chosen time slots.</li>
            </ul>
          ',
          'cols' => array('player', 'mailAddress', 'mobileNumber', 'team', 'chosen_1', 'ass_1', 'chosen_2', 'ass_2')
        )
      );
      
      $th = array (
        'player' => 'Player',
        'main' => 'M-ID',
        'classics' => 'C-ID',
        'place' => 'Main',
        'classicsPlace' => 'Classics',
        'team' => 'Team',
        'tshirts' => 'T-shirts',
        'total' => 'Total',
        'paid' => 'Paid',
        'diff' => 'Diff',
        'payDate' => 'Payment time',
        'id' => 'ID',
        'ifpa' => 'IFPA',
        'mailAddress' => 'Email',
        'mobileNumber' => 'Cell phone',
        'username' => 'User',
        'password' => 'Reset pw',
        'uid' => 'UID',
        'admin' => 'Admin',
        'here' => 'Here',
        'chosen_1' => 'Chosen M',
        'ass_1' => 'Assigned M',
        'chosen_2' => 'Chosen C',
        'ass_2' => 'Assigned C'
      );
      
      foreach ($types as $type) {
        $mailAddresses[$type] = [];
        $tables[$type] = ($type == 'users') ? '<input type="hidden" id="resetNonce" value="'.ulNonce::Create('resetNonce').'"><input type="hidden" id="adminReset" value="true">' : '';
        $tables[$type] .= $meta[$type]['header'].'<table><thead><tr>';
        foreach ($meta[$type]['cols'] as $col) {
          $tables[$type] .= '<th class="bold nowrap">'.$th[$col].'</th>';
        }
        $tables[$type] .= '</tr></thead><tbody>';
      }

      $players = getPlayers($dbh, ' where tournamentEdition_id = 1');
      foreach($players as $player) {
        $team = $player->getTeam($dbh);
//        $team = ($team) ? $team : $player->getTeam($dbh, 12);

        $costs = $player->getCosts($dbh, 'all', 'SEK')['all']['SEK'];
        $diff = $costs - $player->paid;
        $tShirts = $player->getTshirts($dbh);
  
        $total['costs'] += $costs;
        $total['paid'] += $player->paid;
        $total['noOfPaid'] += ($player->paid) ? 1 : 0;
        $total['all']++;
        $total['main'] += $player->main;
        $total['classics'] += $player->classics;
        $total['team'] += ($team) ? 1 : 0;
        $total['tShirts'] += ($tShirts) ? count($tShirts) : 0;
        
        for($i = 0; $i <= 1500; $i = $i + 100){
          $paidOptions[$player->id] .= '<option value="'.$i.'"';
          $paidOptions[$player->id] .= ($i == $player->paid) ? ' selected' : '';
          $paidOptions[$player->id] .= '>'.$i.'</option>';
        }

        $adminLevels = array(0 => 'No', 1 => 'Admin', 2 => 'Scorekeeper');
        for($i = 0; $i <= 2; $i++){
          $adminOptions[$player->id] .= '<option value="'.$i.'"';
          $adminOptions[$player->id] .= ($i == $player->adminLevel) ? ' selected' : '';
          $adminOptions[$player->id] .= '>'.$adminLevels[$i].'</option>';
        }
        
        for($i = 0; $i <= count($players); $i++){
          $placeOptions[$player->id] .= '<option value="'.$i.'"';
          $placeOptions[$player->id] .= ($i == $player->place) ? ' selected' : '';
          $placeOptions[$player->id] .= '>'.$i.'</option>';
        }

        for($i = 0; $i <= count($players); $i++){
          $wpprPlaceOptions[$player->id] .= '<option value="'.$i.'"';
          $wpprPlaceOptions[$player->id] .= ($i == $player->wpprPlace) ? ' selected' : '';
          $wpprPlaceOptions[$player->id] .= '>'.$i.'</option>';
        }

        for($i = 0; $i <= count($players); $i++){
          $classicsPlaceOptions[$player->id] .= '<option value="'.$i.'"';
          $classicsPlaceOptions[$player->id] .= ($i == $player->classicsPlace) ? ' selected' : '';
          $classicsPlaceOptions[$player->id] .= '>'.$i.'</option>';
        }

        for($i = 0; $i <= count($players); $i++){
          $classicsWpprPlaceOptions[$player->id] .= '<option value="'.$i.'"';
          $classicsWpprPlaceOptions[$player->id] .= ($i == $player->classicsWpprPlace) ? ' selected' : '';
          $classicsWpprPlaceOptions[$player->id] .= '>'.$i.'</option>';
        }

        $td = array(
          'player' => '<td>'.$player->getLink().'&nbsp;'.$player->getQR(true, true).'</td>',
          'main' => '<td>'.$player->mainPlayerId.'</td>',
          'classics' => '<td>'.$player->classicsPlayerId.'</td>',
          'team' => '<td>'.(($team) ? '<a href="'.$team->getLink(false).'" target="_blank">'.$team->id.'</a>' : '').'</td>',
          'tshirts' => '<td>'.(($tShirts) ? count($tShirts) : 0).'</td>',
          'paid' => '
            <td>
              <select id="'.$player->id.'_paid" onchange="adminPaidChange(this);" previous="'.$player->paid.'">'.$paidOptions[$player->id].'</select>
              <span class="error errorSpan toolTip" id="'.$player->id.'_paidSpan"></span>
            </td>
            ',
          'diff' => '<td id="'.$player->id.'_diff">'.$diff.'</td>',
          'total' => '<td id="'.$player->id.'_costs">'.$costs.'</td>',
          'payDate' => '<td id="'.$player->id.'_payDate">'.(($player->payDate) ? $player->payDate : 'N/A').'</td>',
          'ifpa' => '<td>'.str_replace('Unranked', 'Unr', $player->getIfpaLink()).'</td>',
          'mailAddress' => '<td class="emailTd"><a href="mailto:'.$player->mailAddress.'">'.$player->mailAddress.'</a></td>',
          'uid' => '<td>'.$ulogin->Uid($player->username).'</td>',
          'admin' => '
              <td>
                <select id="'.$player->id.'_admin" onchange="adminAdmin(this);" previous="'.$player->adminLevel.'">'.$adminOptions[$player->id].'</select>
                <span class="error errorSpan toolTip" id="'.$player->id.'_adminSpan"></span>
              </td>
            ',
          'place' => '
              <td>
                Place: <select id="'.$player->id.'_1_place" onchange="adminPlace(this);" previous="'.$player->place.'">'.$placeOptions[$player->id].'</select>
                <span class="error errorSpan toolTip" id="'.$player->id.'_1_placeSpan"></span>
                WPPR: <select id="'.$player->id.'_1_wppr" onchange="adminPlace(this);" previous="'.$player->wpprPlace.'">'.$wpprPlaceOptions[$player->id].'</select>
                <span class="error errorSpan toolTip" id="'.$player->id.'_1_wpprSpan"></span>
              </td>
            ',
          'classicsPlace' => '
              <td>
                '.(($player->classics) ? '
                Place: <select id="'.$player->id.'_2_place" onchange="adminPlace(this);" previous="'.$player->classicsPlace.'">'.$classicsPlaceOptions[$player->id].'</select>
                <span class="error errorSpan toolTip" id="'.$player->id.'_2_placeSpan"></span>
                WPPR: <select id="'.$player->id.'_2_wppr" onchange="adminPlace(this);" previous="'.$player->classicsWpprPlace.'">'.$classicsWpprPlaceOptions[$player->id].'</select>
                <span class="error errorSpan toolTip" id="'.$player->id.'_2_wpprSpan"></span>
                ' : '').'
              </td>
            ',
          'here' => '
            <td class="nowrap">
              Q:<input type="checkbox" id="'.$player->id.'_qual_here" onclick="adminHere(this);" '.(($player->here) ? 'checked' : '').'>
              <span class="error errorSpan toolTip" id="'.$player->id.'_qual_hereSpan"></span>
              F:<input type="checkbox" id="'.$player->id.'_final_here" onclick="adminHere(this);" '.(($player->hereFinal) ? 'checked' : '').'>
              <span class="error errorSpan toolTip" id="'.$player->id.'_final_hereSpan"></span>
              '.(($player->volunteer) ? 
              'V:<input type="checkbox" id="'.$player->id.'_vol_here" onclick="adminHere(this);" '.(($player->hereVol) ? 'checked' : '').'>
              <span class="error errorSpan toolTip" id="'.$player->id.'_vol_hereSpan"></span>' : '').'
              <a href="'.__baseHref__.'/?S=arrive&playerId='.$player->id.'" target="_blank"><img src="'.__baseHref__.'/images/arrive.png" class="icon right" alt="Nyanländ" title="Klicka här för checklistan för nyanlända"></a>
            </td>
            ',
          'id' => '<td>'.$player->id.'</td>',
          'mobileNumber' => '<td>'.$player->mobileNumber.'</td>',
          'username' => '<td>'.$player->username.'</td>',
          'password' => '<td><input type="button" value="Reset" id="'.$player->id.'_passwordBtn" onclick="resetPassword(this)" class="passwordBtn"><span class="error errorSpan toolTip" id="'.$player->id.'_passwordBtnSpan"></span></td>'
        );
        if (!$tableType || $tableType == 'qualGroups') {
          $qualGroups = $player->getQualGroups($dbh);
          unset($chosen);
          unset($chosens);
          unset($prefered);
          unset($preferedId);
          unset($options);
          if (count($qualGroups) > 0) {
            foreach ($qualGroups as $qualGroup) {
              if ($qualGroup->prefered) {
                $prefered[$qualGroup->tournamentDivision_id] = $qualGroup->shortName;
                $preferedId[$qualGroup->tournamentDivision_id] = $qualGroup->id;
                $chosen[$qualGroup->tournamentDivision_id] .= '<span class="bold">';
              }
              $chosen[$qualGroup->tournamentDivision_id] .= $qualGroup->shortName;
              $chosens[$qualGroup->tournamentDivision_id][] = $qualGroup->id;
              $chosen[$qualGroup->tournamentDivision_id] .= ($qualGroup->prefered) ? '</span> ' : ' ';
            }
          }
          $qualGroups = getQualGroups($dbh);
          $teamNoQualIds = array(0);
          $mainClassicsDiff = 6;
          foreach ($qualGroups as $qualGroup) {
            if (!($team && (in_array($qualGroup->id, $teamNoQualIds)))) {
              $selectedQualGroupId[$qualGroup->tournamentDivision_id] = ($qualGroup->tournamentDivision_id == 1) ? $player->mainQualGroup_id : $player->classicsQualGroup_id;
              $options[$qualGroup->tournamentDivision_id] .= '<option value="'.$qualGroup->id.'" ';
              $options[$qualGroup->tournamentDivision_id] .= ($qualGroup->id == $selectedQualGroupId[$qualGroup->tournamentDivision_id]) ? 'selected' : '';
              $options[$qualGroup->tournamentDivision_id] .= ($qualGroup->id == $preferedId[$qualGroup->tournamentDivision_id]) ? ' class="yellow"' : '';
              $options[$qualGroup->tournamentDivision_id] .= '>'.$qualGroup->shortName.'</option>';
            }
          }
          for($i = 1; $i <= 2; $i++){
            if ($selectedQualGroupId[2] == $selectedQualGroupId[1] + $mainClassicsDiff) {
              $assClass = 'errorTd';
            } else if ($preferedId[$i] == $selectedQualGroupId[$i]) {
              $assClass = 'green';              
            } else if ($chosens[$i] && in_array($selectedQualGroupId[$i], $chosens[$i])) {
              $assClass = 'yellow';
            } else {
              $assClass = '';  
            }
            $td['chosen_'.$i] = '<td'.(($team && (in_array($preferedId[$i], $teamNoQualIds))) ? ' class="errorTd"' : '').'>'.$chosen[$i].'</td>';
            $td['ass_'.$i] = '
                <td class="'.$assClass.'" id="'.$player->id.'_'.$i.'_qualGroupTd">
                  <select id="'.$player->id.'_'.$i.'_qualGroup" onchange="adminQualGroup(this);" previous="'.$selectedQualGroupId[$i].'"'.(($i == 2 && !$player->classics) ? 'disabled' : '').'>
                  <option value="0">...</option>
                  '.$options[$i].'
                  </select>
                  <span class="error errorSpan toolTip" id="'.$player->id.'_'.$i.'_qualGroupSpan"></span>
                  <input type="hidden" id="'.$player->id.'_'.$i.'_qualGroupPref" value="'.$preferedId[$i].'">
                  <input type="hidden" id="'.$player->id.'_'.$i.'_qualGroupChosen" value="'.(($chosens[$i]) ? implode('_', $chosens[$i]) : '0').'">
                </td>
            ';
          }
        }
        foreach ($types as $type) {
          $tables[$type] .= '<tr>';
          foreach ($meta[$type]['cols'] as $col) {
            $tables[$type] .= $td[$col];
          }
          $tables[$type] .= '</tr>';
          array_push($mailAddresses[$type], $player->mailAddress);
        }
      }
      foreach ($types as $type) {
        
        if ($type == 'payments') {
          $tables[$type] .= '
                <tr>
                  <td class="bold">&nbsp;Sum</td>
                  <td class="bold">'.$total['all'].'</td>
                  <td class="bold">'.$total['main'].' ('.$total['noOfPaid'].')</td>
                  <td class="bold">'.$total['classics'].'</td>
                  <td class="bold">'.$total['team'].'</td>
                  <td class="bold">'.$total['tShirts'].'</td>
                  <td class="bold" id="total_costs">'.$total['costs'].' ('.round($total['costs'] / $total['main']).')</td>
                  <td class="bold" id="total_paid">'.$total['paid'].' ('.round($total['paid'] / $total['noOfPaid']).')</td>
                  <td class="bold" id="total_diff">'.($total['costs'] - $total['paid']).'</td>
                  <td class="bold"></td>
                </tr>
              </tbody>
            </table>
          ';
        } else {
          $tables[$type] .= '</tbody></table>';
        }
        $tables[$type] .= '
          <br /><br /><h2 class="entry-title">Mail addresses</h2>
          <p>Here are all mail addresses of the people in the above table, to copy and paste for emailing:</p>
          <p>'.implode(', ', $mailAddresses[$type]).'</p>
        ';
      }
      return ($tableType) ? $tables[$tableType] : $tables;
    }
  
    function getAdminPasswordResetForm($dbh, $player = false) {
      $content .= '<h2 class="entry-title">Password reset</h2>';
      $content .= ($player) ? '<input type="hidden" id="personId" value="'.$player->id.'">' : '<label>Player: </label>'.createSelect(getObjectList($dbh, 'player', array ('tournament' => '1')), 'personId', 0, '');
      $content .= '<label>New password: </label>';
      $content .= '<input type="text" value="!chAng3m3" id="password">';
      $content .= '<input type="button" value="Reset" onclick="resetPassword(this)">';
      $content .= '<span id="passwordSpan" class="errorSpan">Please, do not use too simple passwords</span>';
      $content .= '<input type="hidden" id="resetNonce" value="'.ulNonce::Create('resetNonce').'">';
      $content .= '<input type="hidden" id="adminReset" value="true">';
      return $content;
    }
  
    function getAdminVolunteerTable($dbh, $type = 'all') {
      $volTable = '<br /><br /><h2 class="entry-title">Volunteers</h2>';
      // $volTable .= getAdminChangeForm($dbh, getVolunteers($dbh), 'hours');
      $volunteers = getVolunteers($dbh);
      $tasks = getTasks($dbh);
      $periods = getPeriods($dbh);
      $teamPeriods = array(0);
      $mainTasks = array(1, 3, 4);
      $volTable .= '
        <table>
          <thead>
            <tr>
              <th class="bold">Player</th>
              <th class="bold">Phone</th>
              <th class="bold">Cell</th>
              <th class="bold">Email</th>
              <th class="bold">Alloc</th>
              <th class="bold">Here</th>
            </tr>
          </thead>
          <tbody>
      ';
//              <th class="bold">Hours</th>
//              <th class="bold">Diff</th>
      foreach ($volunteers as $volunteer) {
        $volTable .= '
              <tr>
                <td>'.$volunteer->getLink().'</td>
                <td>'.$volunteer->telephoneNumber.'</td>
                <td>'.$volunteer->mobileNumber.'</td>
                <td class="emailTd"><a href="mailto:'.$volunteer->mailAddress.'">'.$volunteer->mailAddress.'</a></td>
                <td id="'.$volunteer->id.'_alloc">'.preg_replace('/^0+/', '', preg_replace('/:00$/', '', preg_replace('/:00$/', '', $volunteer->alloc))).'</td>
                <td>
                  <input type="checkbox" id="'.$volunteer->id.'_vol_here" onclick="adminHere(this);" '.(($volunteer->hereVol) ? 'checked' : '').'>
                  <span class="error errorSpan toolTip" id="'.$volunteer->id.'_vol_hereSpan"></span>
                </td>
              </tr>
        ';
/*
                <td>
                  <select id="'.$volunteer->id.'_hours" onchange="adminHoursChange(this);" previous="'.$volunteer->hours.'">
          ';
          for($i = 0; $i <= 100; $i++){
            $volTable .= '<option value="'.$i.'" ';
            $volTable .= ($i == $volunteer->hours) ? 'selected' : '';
            $volTable .= '>'.$i.'</option>';
          }
          $volTable .= '
                  </select>
                  <span class="error errorSpan toolTip" id="'.$volunteer->id.'_hoursSpan"></span>
                </td>
                <td id="'.$volunteer->id.'_diff">'.preg_replace('/^0+/', '', preg_replace('/:00$/', '', preg_replace('/:00$/', '', $volunteer->hoursDiff))).'</td>
*/
//        $total['hours'] += $volunteer->hours;
        $total['alloc'] += $volunteer->alloc;
        $total['here'] += ($volunteer->hereVol) ? 1 : 0;
//        $total['diff'] += $volunteer->hoursDiff;
      }
      $volTable .= '
            <tr>
              <td class="bold">&nbsp;Sum</td>
              <td></td>
              <td></td>
              <td></td>
              <td id="total_alloc" class="bold">'.$total['alloc'].'</td>
              <td id="total_here" class="bold">'.$total['here'].'</td>
            </tr>
      ';
//              <td class="bold">'.$total['hours'].'</td>
//              <td id="total_diff" class="bold">'.$total['diff'].'</td>
      $volTable .= '</tbody></table>';
      $needTable = '
          <br /><br /><h2 class="entry-title">Volunteer requirements (Alloc / Need + Assignments)</h2>
          <ul>
            <li>Hover over a header to get the full task name</li>
            <li>Alloc: The current number of assigned volunteers for that task during that period.</li>
            <li>The numbers dropdown is the need for that task during that period. Change as needed.</li>
            <li>Click the<img src="'.__baseHref__.'/images/edit.png" class="textIcon allocEditIcon" alt="Click to assign a volunteer" title="Click to assign a volunteer"> icon to get a popup where you can assign or change volunteers.</li>
            <li>If you change the need, it will not automatically change the number of volunteer dropdowns in the popup. Reload the page to reflect the changes.</li>
          </ul>
          <label>Show/hide all assignments</label><input type="checkbox" onclick="allocShowAll(this);"> (This will open every "Ass" popup in the table)
          <table>
            <thead>
              <tr>
                <th class="bold">Period</th>
                <th class="bold">Hrs</th>
      ';
      foreach ($tasks as $task) {
        $needTable .= '<th class="bold" title="'.$task->name.'">'.$task->shortName.'</th>';
      }
      $needTable .= '
              </tr>
            </thead>
            <tbody>
      ';
      $assignmentTable = '
          <br /><br /><h2 class="entry-title">Volunteer assignments (tasks / periods)</h2>
          <ul>
            <li>This table contains the same info as above, but limited to the three major tasks, giving a better overview.</li>
            <li>If you change the need above, you need to reload the page to get the right number of dropdowns below.</li>
          </ul>
          <table>
            <thead>
              <tr>
                <th class="bold">Period</th>
                <th class="bold">Hrs</th>
                <th class="bold">Scorekeeper</th>
                <th class="bold">Referee</th>
                <th class="bold">Reception</th>
              </tr>
            </thead>
            <tbody>
      ';
      foreach ($periods as $period) {
        $date = preg_replace('/^2013-[0-9]+-/', '', $period->date).'th';
        $clock = preg_replace('/:00$/', '', preg_replace('/:00$/', '', $period->startTime)).'-'.preg_replace('/:00$/', '', preg_replace('/:00$/', '', $period->endTime));
        $length = preg_replace('/^0+/', '', preg_replace('/:00$/', '', preg_replace('/:00$/', '', $period->length)));
        $length = ($length) ? $length : 0;
        $needTable .= '
          <tr>
            <td>'.$date.'<br />'.$clock.'</td>
            <td><span id="'.$period->id.'_length">'.$length.'</span><br />Need:</td>
        ';
        $assignmentTable .= '
          <tr>
            <td>'.$date.'<br />'.$clock.'</td>
            <td id="'.$period->id.'_assLength">'.$length.'</td>
        ';
        foreach ($tasks as $task) {
          $alloc = $task->getAlloc($dbh, $period);
          $need = $task->getNeed($dbh, $period);
//          $volunteers = $task->getVolunteersByPeriod($dbh, $period);
          $needTable .= '
            <td id="'.$task->id.'_'.$period->id.'_needsTd" class="'.(($alloc < $need) ? 'errorTd' : '').'">
              Alloc: <span id="'.$task->id.'_'.$period->id.'_alloc">'.$alloc.'</span><br />
              <select id="'.$task->id.'_'.$period->id.'_needs" onchange="changeNeed(this);" class="volNeed" previous="'.$need.'">
          ';
          $assignmentTable .= (in_array($task->id, $mainTasks)) ? '<td>' : '';
          for ($i = 0; $i<10; $i++) {
            $needTable .= '<option value="'.$i.'"';
            $needTable .= ($i == $need) ? ' selected ' : '';
            $needTable .= '>'.$i.'</option>';
          }
          $needTable .= '
              </select><br />
              <span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_needsSpan"></span>
              Ass:<img src="'.__baseHref__.'/images/edit.png" class="textIcon allocEditIcon" onclick="adminAlloc(this, true);" alt="Click to assign a volunteer" title="Click to assign a volunteer" id="'.$task->id.'_'.$period->id.'_allocVols">
              <span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_allocVolsSpan"></span>
          ';
          if ($need || $alloc) {
            $needTable .= '
              <div id="'.$task->id.'_'.$period->id.'_allocVolsEdit" class="allocEdit">
                '.$date.' '.$clock.' '.$task->shortName.'
                <img src="'.__baseHref__.'/images/cancel.png" style="float: right" class="textIcon" onclick="adminAlloc(this, false);" alt="Click to close the box" title="Click to close the box" id="'.$task->id.'_'.$period->id.'_allocVolsClose">
                <span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_allocVolsCloseSpan"></span><br />
              
            ';
            $allocVols = $task->getAllocVols($dbh, $period);
            $noOfAllocVols = ($allocVols) ? count($allocVols) : 0;
            if ($allocVols && $noOfAllocVols > 0) {
              foreach($allocVols as $allocVol) {
                $needTable .= '<select id="'.$task->id.'_'.$period->id.'_'.$i.'_allocVol" onchange="allocEdit(this);" previous="'.$allocVol->id.'">';
                $needTable .= '<option value="0">None</option>';
                if (in_array($task->id, $mainTasks)) {
                  $assignmentTable .= '<select id="'.$task->id.'_'.$period->id.'_'.$i.'_allocAss" onchange="allocEdit(this);" previous="'.$allocVol->id.'">';
                  $assignmentTable .= '<option value="0">None</option>';
                }
                if ($volunteers) {
                  foreach ($volunteers as $volunteer) {
                    $team = $volunteer->getTeam($dbh);
//                    $team = ($team) ? $team : $volunteer->getTeam($dbh, 12);
                    if($volunteer->checkIfVolFree($dbh, $period) && !($team && in_array($period->id, $teamPeriods))) {
                      $needTable .= '<option value="'.$volunteer->id.'"';
                      $needTable .= ($volunteer->id == $allocVol->id) ? ' selected' : '';
                      $needTable .= '>'.$volunteer->name.'</option>';
                      if (in_array($task->id, $mainTasks)) {
                        $assignmentTable .= '<option value="'.$volunteer->id.'"';
                        $assignmentTable .= ($volunteer->id == $allocVol->id) ? ' selected' : '';
                        $assignmentTable .= '>'.$volunteer->name.'</option>';
                      }
                    }
                  }
                }
                $needTable .= '
                    </select>
                    <span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_'.$i.'_allocVolSpan"></span><br />
                ';
                $assignmentTable .= (in_array($task->id, $mainTasks)) ? '</select><span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_'.$i.'_allocAssSpan"></span><br />' : '';
                $i++;
              }
            }
            if ($need && (!$allocVols || $need - $noOfAllocVols > 0)) {
              for ($i = 0 + $noOfAllocVols; $i < $need; $i++) {
                $needTable .= '<select id="'.$task->id.'_'.$period->id.'_'.$i.'_allocVol" onchange="allocEdit(this);" previous="0">';
                $needTable .= '<option value="0">None</option>';
                if (in_array($task->id, $mainTasks)) {
                  $assignmentTable .= '<select id="'.$task->id.'_'.$period->id.'_'.$i.'_allocAss" onchange="allocEdit(this);" previous="0">';
                  $assignmentTable .= '<option value="0">None</option>';                
                }
                if ($volunteers) {
                  foreach ($volunteers as $volunteer) {
                    $team = $volunteer->getTeam($dbh);
//                    $team = ($team) ? $team : $volunteer->getTeam($dbh, 12);
                    if($volunteer->checkIfVolFree($dbh, $period) && !($team && in_array($period->id, $teamPeriods))) {
                      $needTable .= '<option value="'.$volunteer->id.'">'.$volunteer->name.'</option>';
                      $assignmentTable .= (in_array($task->id, $mainTasks)) ? '<option value="'.$volunteer->id.'">'.$volunteer->name.'</option>' : '';
                    }
                  }
                }
                $needTable .= '
                    </select>
                    <span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_'.$i.'_allocVolSpan"></span><br />
                ';
                $assignmentTable .= (in_array($task->id, $mainTasks)) ? '</select><span class="error errorSpan toolTip" id="'.$task->id.'_'.$period->id.'_'.$i.'_allocAssSpan"></span><br />' : '';
              }  
            }
            $needTable .= '</div>';
          }
          $needTable .= '
            </td>
          ';
          $assignmentTable .= (in_array($task->id, $mainTasks)) ? '</td>' : '';
        }
      }
      $needTable .= '</tbody></table>';
      $assignmentTable .= '</tbody></table>';
      switch ($type) {
        case 'volunteers':
          return $volTable;
        break;
        case 'needs':
          return $needTable;
        break;
        case 'assignments':
          return $assignmentTable;
        break;
        case 'all':
          return array('volunteers' => $volTable, 'needs' => $needTable, 'assignments' => $assignmentTable);
        break;
        default:
          return array('volunteers' => $volTable, 'needs' => $needTable, 'assignments' => $assignmentTable);
        break;
      }
    }
  
    function getAdminTeamTable($dbh, $national = false) {
      $content = '<br /><br /><h2 class="entry-title">'.(($national) ? 'National' : 'Other').' teams</h2>';
      $content .= '
        <table>
          <thead>
            <tr>
              '.(($national) ? '<th class="bold">Country</th>' : '').'
              <th class="bold">Team</th>
              <th class="bold">Registrator & Captain</th>
              <th class="bold">Members</th>
            </tr>
          </thead>
          <tbody>
      ';
      $countries = getObjs($dbh, 'country');
      if ($national) {
        foreach($countries as $country) {
          $team = getTeamByCountry($dbh, $country->id);
          $teams[] = ($team) ? $team : new team(array('id' => 0, 'country_id' => $country->id, 'country' => $country->name));
        }
      } else {
        $teams = getTeams($dbh);
      }
      foreach($teams as $team) {
        $contact = ($team->contactPlayer_id) ? getPlayerByMainId($dbh, $team->contactPlayer_id) : false;
        $registrator = ($team->registerPerson_id) ? getPersonById($dbh, $team->registerPerson_id) : false;
        $idPrefix = ($national) ? $team->id.'-'.$team->country_id.'_natTeam_' : $team->id.'_team_';
        $members = ($team->id) ? $team->getMembers($dbh) : false;
        $potentialMembers = ($national) ? getPlayersByCountryId($dbh, $team->country_id) : getPlayerList($dbh);
        $potentialRegistrators = $potentialMembers;
        if ($registrator && !$registrator->mainPlayerId) {
          array_push($potentialRegistrators, getPersonById($dbh, $team->registerPerson_id));
        }
        $content .= '
            <tr>
              '.(($national) ? '<td'.(($team->id) ? ' class="bold"' : '').'>'.$team->country.'</td>' : '').'
              <td>
                <input type="text" id="'.$idPrefix.'name" value="'.(($team) ? $team->name : '').'">
                <span class="error errorSpan toolTip" id="'.$idPrefix.'nameSpan"></span>
                <img src="'.__baseHref__.'/images/cancel.png" class="icon" onclick="adminTeam(this);" alt="Click to delete the team" title="Click to delete the team" style="display: '.(($team->id) ? '' : 'none').';" id="'.$idPrefix.'delete">
                <span class="error errorSpan toolTip" id="'.$idPrefix.'deleteSpan"></span>
                <img src="'.__baseHref__.'/images/add_icon.gif" class="icon" onclick="adminTeam(this);" alt="Click to add a new team" title="Click to add a new team" style="display: '.(($team->id) ? 'none' : '').';" id="'.$idPrefix.'add">
                <span class="error errorSpan toolTip" id="'.$idPrefix.'addSpan"></span>
                <br />
                <input size="3em" type="text" id="'.$idPrefix.'initials" value="'.$team->initials.'">
                <span class="error errorSpan toolTip" id="'.$idPrefix.'initialsSpan"></span>
                <input type="button" value="Change name" id="'.$idPrefix.'nameChange" style="display: '.(($team->id) ? '' : 'none').';" title="Click to change name and initials" onclick="adminTeam(this);">
                <span class="error errorSpan toolTip" id="'.$idPrefix.'nameChangeSpan"></span>
              </td>
              <td>
              R: '.createSelect($potentialRegistrators, $idPrefix.'regSelect', (($registrator) ? $registrator->id : 0), 'adminTeam', (($team->id) ? '' : 'disabled')).'
              <span class="error errorSpan toolTip" id="'.$idPrefix.'regSelectSpan"></span>
              <br />
              C: '.createSelect($members, $idPrefix.'contactSelect', (($contact) ? $contact->id : 0), 'adminTeam', (($team->id) ? '' : 'disabled')).'
              <span class="error errorSpan toolTip" id="'.$idPrefix.'contactSelectSpan"></span>
              </td>
              <td>
        ';
        for($i = 1; $i < 3; $i++) {
          $content .= ($i == 3) ? '<br />' : '';
          $content .= createSelect($potentialMembers, $idPrefix.'memberSelect_'.$i, (($members[$i - 1]) ? $members[$i - 1]->id : 0), 'adminTeam', (($team->id) ? '' : 'disabled'));
          $content .= '<span class="error errorSpan toolTip" id="'.$idPrefix.'memberSelect_'.$i.'Span"></span>';
        }
        $content .= '</td></tr>';
      }
      return $content.'</tbody></table>';
    }
  
    function getAdminTshirtTable($dbh, $type = 'total') {
      $totalTable .= '
        <br /><br /><h2 class="entry-title">T-shirts total</h2>
        <ul>
          <li>Total: The number we ordered from our supplier of that color and size.</li>
          <li>Reservers: The number of people that reserved a T-shirt of that color and size.</li>
          <li>Reserved: The number of pre-ordered T-shirts of that color and size.</li>
          <li>Delivered: The number of pre-ordered T-shirts already handed out to the players.</li>
          <li>Sold on site: The number of NOT pre-ordered T-shirts sold on site.</li>
          <ul>
            <li>Click the<img src="'.__baseHref__.'/images/add_icon.gif" class="textIcon" alt="Increase number of sold T-shirts" title="Increase number of sold T-shirts"> icon if you sell one of the NOT pre-ordered T-shirts.</li>
            <li>ONLY click the<img src="'.__baseHref__.'/images/minus.png" class="textIcon" alt="Decrease number of sold T-shirts" title="Decrease number of sold T-shirts"> icon if you mistakenly click the icon once too much.</li>
          </ul>
          <li>In stock: The number of T-shirt still in stock.
          <ul>
            <li>This includes both pre-ordered T-shirts not handed out yet, and T-shirts for sale on site.</li>
          </ul>
          <li>For sale: The number of NOT pre-ordered T-shirts that can still be sold on site.</li>
          <ul>
            <li>DO NOT sell T-shirts if this number is zero or negative!</li>
          </ul>
        </ul>
        <table>
          <thead>
            <tr>
              <th class="bold">T-shirt</th>
              <th class="bold">Total</th>
              <th class="bold">Reservers</th>
              <th class="bold">Reserved</th>
              <th class="bold">Delivered</th>
              <th class="bold">Sold on site</th>
              <th class="bold">In stock</th>
              <th class="bold">For sale</th>
            </tr>
          </thead>
          <tbody>
      ';
      $tshirts = getNoOfTshirts($dbh);
      foreach($tshirts as $tshirt) {
        $totalTable .= '
          <tr>
            <td>'.$tshirt->name.'</td>
            <td>'.$tshirt->total.'</td>
            <td>'.$tshirt->buyers.'</td>
            <td>'.$tshirt->reserved.'</td>
            <td id="'.$tshirt->tournamentTshirt_id.'_tshirtDlvr">'.$tshirt->delivered.'</td>
            <td>
              <img src="'.__baseHref__.'/images/minus.png" class="textIcon" onclick="adminTshirtSold(this);" alt="Decrease number of sold T-shirts" title="Decrease number of sold T-shirts" id="'.$tshirt->tournamentTshirt_id.'_tshirtDelete">
              <span class="error errorSpan toolTip" id="'.$tshirt->tournamentTshirt_id.'_tshirtDeleteSpan"></span>
              <span id="'.$tshirt->tournamentTshirt_id.'_tshirtSold">'.$tshirt->soldOnSite.'</span>
              <img src="'.__baseHref__.'/images/add_icon.gif" class="textIcon" onclick="adminTshirtSold(this);" alt="Increase number of sold T-shirts" title="Increase number of sold T-shirts" id="'.$tshirt->tournamentTshirt_id.'_tshirtAdd">
              <span class="error errorSpan toolTip" id="'.$tshirt->tournamentTshirt_id.'_tshirtAddSpan"></span>
            </td>
            <td id="'.$tshirt->tournamentTshirt_id.'_tshirtStock">'.($tshirt->total - $tshirt->delivered - $tshirt->soldOnSite).'</td>
            <td id="'.$tshirt->tournamentTshirt_id.'_tshirtForSale" class="bold">'.($tshirt->total - $tshirt->reserved - $tshirt->soldOnSite).'</td>
          </tr>
        ';
      }
      $totalTable .= '</tbody></table>';
      $buyersTable .= '
        <br /><br /><h2 class="entry-title">Reserved T-shirts</h2>
        <ul>
          <li>A player can be listed several times, if (s)he made several T-shirt orders</li>
          <li>Paid is the full amount of paid SEK - including not only T-shirts, but also tournament fees.</li>
          <ul>
            <li>If the player gives you money, the paid dropdown should be changed accordingly.</li>
            <li>This is the total - if the player has already paid 200 and give you 200 more, change this to 400.</li>
          </ul>
          <li>Dlvr: Click this checkbox when the T-shirt order has been handed out to the player</li>
          <ul>
            <li>If the player has made several T-shirt orders, make sure to click all his/her "Dlvr" checkboxes!</li>
          </ul>
        </ul>
      ';
//      $buyersTable .= getCurCalcForm();
      $buyersTable .= '
        <table>
          <thead>
            <tr>
              <th class="bold">Player</th>
              <th class="bold">Email</th>
              <th class="bold">Cell phone</th>
              <th class="bold">Qty</th>
              <th class="bold">Color</th>
              <th class="bold">Size</th>
              <th class="bold">Paid</th>
              <th class="bold">Dlvr</th>
            </tr>
          </thead>
          <tbody>
      ';
      $tshirtOrders = getTshirtOrders($dbh);
      foreach ($tshirtOrders as $tshirtOrder) {
        $buyer = getPlayerById($dbh, $tshirtOrder->player_id);
        $costs = $buyer->getCosts($dbh, 'all', 'SEK')['all']['SEK'];
        $buyersTable .= '
          <tr>
            <td>'.$buyer->getLink().'</td>
            <td class="emailTd"><a href="mailto:'.$buyer->mailAddress.'">'.$buyer->mailAddress.'</a></td>
            <td>'.$buyer->mobileNumber.'</td>
            <td>'.$tshirtOrder->number.'</td>
            <td>'.$tshirtOrder->color.'</td>
            <td>'.$tshirtOrder->size.'</td>
        ';
        $buyersTable .= '
            <td>
              <select id="'.$buyer->id.'_'.$tshirtOrder->id.'_paid" onchange="adminPaidChange(this);" previous="'.$buyer->paid.'" class="'.$buyer->id.'_paid">
        ';
        for($i = 0; $i < 1500; $i = $i + 100){
          $buyersTable .= '<option value="'.$i.'" ';
          $buyersTable .= ($i == $buyer->paid) ? 'selected' : '';
          $buyersTable .= '>'.$i.'</option>';
        }
        $buyersTable .= '
              </select><span class="error errorSpan toolTip" id="'.$buyer->id.'_'.$tshirtOrder->id.'_paidSpan"></span>
 of <span id="'.$buyer->id.'_costs">'.$costs.'</span>
            </td>
            ';
        $buyersTable .= '
            <td>
              <input type="checkbox" id="'.$tshirtOrder->playerTshirt_id.'_dlvr" onclick="tshirtDlvr(this);" '.(($tshirtOrder->dateDelivered) ? 'checked' : '').'>
              <span class="error errorSpan toolTip" id="'.$tshirtOrder->playerTshirt_id.'_dlvrSpan"></span>
            </td>
          </tr>
        ';
      }
      $buyersTable .= '</tbody></table>';
      return ($type == 'buyers') ? $buyersTable : $totalTable;
    }
    
    function getAdminGameTable($dbh) {
      $allGames = getGames($dbh, false, 'order by g.name', 0);
      $gameTable .= '<br /><br /><h2 class="entry-title">Game tools</h2>';
      $gameTable .= '
        <table id="adminGameTable">
          <thead>
            <tr>
              <th class="bold">Game</th>
              <th class="bold">Acronym</th>
              <th class="bold">Manufacturer</th>
              <th class="bold">IPDB</th>
              <th class="bold">Rules</th>
              <th class="bold">Type</th>
              <th class="bold">Usage</th>
            </tr>
          </thead>
          <tbody>
            <tr id="0_gameTd">
              <td>
                '.createSelect($allGames, '0_game', 0, 'adminGameNew', '', 'Add game...').'
                <span class="error errorSpan toolTip" id="0_gameSpan"></span>
              </td>
              <td id="0_acroTd"></td>
              <td id="0_manufacturerTd"></td>
              <td id="0_ipdbTd"></td>
              <td id="0_rulesTd"></td>
              <td id="0_typeTd"></td>
              <td id="0_usageTd">
                <span class="error errorSpan toolTip" id="0_usageSpan"></span>
                <img src="'.__baseHref__.'/images/add_icon.gif" class="icon right" onclick="adminGameAdd(this);" alt="Add game to tournament" title="Add game to tournament" id="0_gameAdd">
                <span class="error errorSpan toolTip" id="0_gameAddSpan"></span>
              </td>
            </tr>
      ';      
      $games = getGames($dbh, false, 'order by g.name', 1, '');
      foreach($games as $game) {
        $gameTable .= '
          <tr>
            <td id="'.$game->id.'_gameTd">
              '.$game->getLink().'&nbsp;'.$game->getQR(true, true).'
              <img src="'.__baseHref__.'/images/edit.png" class="icon right" onclick="adminGameEdit(this, true);" alt="Click to view/edit the game properties" title="Click to view/edit the game properties" id="'.$game->machine_id.'_edit">
              <div class="toolTip" id="'.$game->machine_id.'_editDiv">
                <img src="'.__baseHref__.'/images/cancel.png" class="icon right" onclick="adminGameEdit(this, false);" alt="Click to remove the game from the tournament" title="Click to remove the game from the tournament" id="'.$game->machine_id.'_editDivClose">
                <div id="'.$game->machine_id.'_ballsDiv" class="right">
                  Number of balls: <select id="'.$game->machine_id.'_balls" onchange="adminGameBalls(this);" previous="'.$game->balls.'">
                    <option value="0">Balls...</option>
                    <option value="3"'.(($game->balls == '3') ? ' selected' : '').'>3</option>
                    <option value="5"'.(($game->balls == '5') ? ' selected' : '').'>5</option>
                  </select>
                  <span class="error errorSpan toolTip" id="'.$game->machine_id.'_ballsSpan"></span>
                </div>
                <div id="'.$game->machine_id.'_onePlayerAllowedDiv">
                  One player allowed: <input type="checkbox" id="'.$game->machine_id.'_onePlayerAllowed" onclick="adminGameOnePlayerAllowed(this);" '.(($game->onePlayerAllowed) ? 'checked' : '').'>
                  <span class="error errorSpan toolTip" id="'.$game->machine_id.'_onePlayerAllowedSpan"></span>
                </div>
                <div id="'.$game->machine_id.'_commentDiv">
                  Comment: <input type="text" id="'.$game->machine_id.'_comment" value="'.$game->comment.'" class="dbComment" onkeyup="enterClick(\''.$game->machine_id.'_commentSubmit\', event);">
                  <input type="button" id="'.$game->machine_id.'_commentSubmit" onclick="adminGameComment(this);" value="Change!">
                  <span class="error errorSpan toolTip" id="'.$game->machine_id.'_commentSubmitSpan"></span>
                </div>
              </div>
            </td>
            <td id="'.$game->id.'_acroTd">'.$game->shortName.'</td>
            <td id="'.$game->id.'_manufacturerTd">'.$game->manufacturer.'</td>
            <td id="'.$game->id.'_ipdbTd">'.$game->getIpdbLink().'</td>
            <td id="'.$game->id.'_rulesTd">'.$game->getRulesLink().'</td>
            <td id="'.$game->id.'_typeTd">
              <select id="'.$game->id.'_type" onchange="adminGameType(this);" previous="'.$game->gameType.'">
                <option value="0">Type...</option>
                <option value="modern"'.(($game->gameType == 'modern') ? ' selected' : '').'>Modern</option>
                <option value="classics"'.(($game->gameType == 'classics') ? ' selected' : '').'>Classics</option>
              </select>
              <span class="error errorSpan toolTip" id="'.$game->id.'_typeSpan"></span>
            </td>
            <td id="'.$game->id.'_usageTd">
              <select id="'.$game->machine_id.'_usage" onchange="adminGameUsage(this);" previous="'.$game->tournamentDivision_id.'">
                <option value="0">Usage...</option>
                <option value="1"'.(($game->tournamentDivision_id == 1) ? ' selected' : '').'>Main</option>
                <option value="2"'.(($game->tournamentDivision_id == 2) ? ' selected' : '').'>Classics</option>
                <option value="3"'.(($game->tournamentDivision_id == 3) ? ' selected' : '').'>Team</option>
                <option value="13"'.(($game->tournamentDivision_id == 13) ? ' selected' : '').'>Side</option>
                <option value="14"'.(($game->tournamentDivision_id == 14) ? ' selected' : '').'>Recreational</option>
              </select>
              <span class="error errorSpan toolTip" id="'.$game->machine_id.'_usageSpan"></span>
              <img src="'.__baseHref__.'/images/cancel.png" class="icon right" onclick="adminGameDel(this);" alt="Click to remove the game from the tournament" title="Click to remove the game from the tournament" id="'.$game->machine_id.'_delete">
              <span class="error errorSpan toolTip" id="'.$game->machine_id.'_deleteSpan"></span>
            </td>
          </tr>
        ';
      }
      $gameTable .= '</tbody></table>';
      return $gameTable;
    }
    
    function getAdminQualGroupTable($dbh) {
      $content .= '<br /><br /><h2 class="entry-title">Qualification groups</h2>';
      $qualGroups = getQualGroups($dbh);
      $qualLimit['Main'] = __mainQualLimit__;
      $qualLimit['Classics'] = __classicsQualLimit__;
      $qualLimit['main'] = 60;
      $qualLimit['classics'] = 30;
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
          $type = ($tournamentDivisionId == 1) ? 'Main' : 'Classics';
          $content .= '
            <br /><br /><h2 class="entry-title">'.$type.' tournament</h2>
            <table>
              <thead>
                <tr>
                  <th class="bold">Name</th>
                  <th class="bold">Date</th>
                  <th class="bold">Start</th>
                  <th class="bold">End</th>
                  <th class="bold">Chosen</th>
                  <th class="bold">Prefered</th>
                  <th class="bold">Assigned</th>
                  <th class="bold">Max</th>
                </tr>
              </thead>
              <tbody>
          ';
          foreach($qualGroupsByDiv[$tournamentDivisionId] as $qualGroup) {
            $content .= '
                <tr>
                  <td>'.$qualGroup->shortName.'</td>
                  <td>'.$qualGroup->date.'</td>
                  <td>'.$qualGroup->startTime.'</td>
                  <td>'.$qualGroup->endTime.'</td>
                  <td>'.$qualGroup->getNoOfPlayers($dbh).'</td>
                  <td>'.$qualGroup->getNoOfPlayers($dbh, true).'</td>
                  <td id="'.$qualGroup->id.'_assigned">'.$qualGroup->getNoOfAssignedPlayers($dbh).'</td>
                  <td>'.$qualLimit[$type].'</td>
                </tr>
            ';
          }
          $content .= '</tbody></table>';
        } 
      } else {
        $content .= 'Something must have gone wrong, because I can\'t find any qualification groups in the database?';
      }
      return $content;
    }
    
  }
  
?>