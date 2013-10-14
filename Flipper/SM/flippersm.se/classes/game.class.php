<?php
  
  class game extends base {
    
    public $isIpdb;
    public $ipdb_id;
    public $year;
    public $rules;
    public $classics;
    public $main;
    public $team;
    public $natTeam;
    public $machine_id;
    public $side;
    public $recreational;
    public $machineType;
    public $gameType;
    public $extraBall;
    public $balls;
    public $onePlayerAllowed;
    public $type;
    public $manufacturer;
    public $manufacturer_id;
    public $owner_id;
    public $owner;
    public $ownerShortName;
    public $class = 'game';
   
    function getOwnerLink($href = true, $target = '_blank') {
      if ($this->owner_id) {
        $url = __baseHref__.'/ajax/getOwner.php&id='.$this->owner_id;
        return ($href) ? '<a href="'.$url.'" title="'.$this->owner.'" '.(($target) ? 'target="'.$target.'"' : '').'>'.$this->ownerShortName.'</a>' : $url;
      } else {
        return false;
      }
    }

    function getOwnerPopup() {
      if ($this->owner_id) {
        return '
          <span title="'.$this->owner.'" class="link" onclick="ownerDiv(this, true);" id="'.$this->machine_id.'_ownerInfoDiv_open">'.$this->ownerShortName.'</span>
        ';
      } else {
        return false;
      }
    }

    function getIpdbLink($href = true, $target = '_blank') {
      if ($this->ipdb_id) {
        $url = 'http://ipdb.org/machine.cgi?id='.$this->ipdb_id;
        return ($href) ? '<a href="'.$url.'" title="Click to go to IPDB.org" '.(($target) ? 'target="'.$target.'"' : '').'>'.$this->ipdb_id.'</a>' : $url;
      } else {
        return false;
      }
    }
    
    function getRulesLink($href = true, $target = '_blank') {
      if ($this->ipdb_id) {
        return ($href) ? '<a href="'.$this->rules.'" title="Click to view game rules and strategys on an external site" '.(($target) ? 'target="'.$target.'"' : '').'>Rules</a>' : $this->rules;
      } else {
        return false;
      }
    }
    
    function getOwnerInfo($dbh) {
      $owner = getOwnerById($dbh, $this->owner_id);
      if ($owner) {
        $player = getPlayerById($dbh, $owner->contactPerson_id);
        $city = getCityById($dbh, $owner->city_id);
        return '
          <div class="toolTip" id="'.$this->machine_id.'_ownerInfoDiv">
            <img src="'.__baseHref__.'/images/cancel.png" class="icon right" onclick="ownerDiv(this, false);" alt="Click to close this popup" title="Click to close this popup" id="'.$this->machine_id.'_ownerInfoDiv_close">
            Owner ID: '.$owner->id.'
            <br />Owner: '.$owner->name.' ('.$owner->shortName.')
            '.(($player) ? '<br />Contact: '.$player->getLink() : '').'
            <br />Address: '.$owner->streetAddress.', '.$owner->zipCode.(($city) ? ', '.$city->getLink() : '').'
            <br />Phone: '.$owner->telephoneNumber.' '.$owner->mobileNumber.'
            <br />Email: <a href="mailto:'.$owner->mailAddress.'">'.$owner->mailAddress.'</a>
          </div>
        ';
      } else {
        return false;
      }
    }

    function setType($dbh, $type) {
      $query = 'update machine set gameType = :type where game_id = :id';
      $update[':type'] = $type;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      deNorm($dbh, 'machine');
      return ($sth->execute($update)) ? true : false;
    }

    function setUsage($dbh, $division = 1) {
      $query = 'update machine set tournamentDivision_id = :division where id = :id';
      $update[':division'] = $division;
      $update[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setBalls($dbh, $balls = 3) {
      $query = 'update machine set balls = :balls where id = :id';
      $update[':balls'] = $balls;
      $update[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setExtraBalls($dbh, $extraBalls = false) {
      $query = 'update machine set extraBalls = :extraBalls where id = :id';
      $update[':extraBalls'] = ($extraBalls) ? 1 : 0;
      $update[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setOnePlayerAllowed($dbh, $onePlayerAllowed = false) {
      $query = 'update machine set onePlayerAllowed = :onePlayerAllowed where id = :id';
      $update[':onePlayerAllowed'] = ($onePlayerAllowed) ? 1 : 0;
      $update[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function add($dbh, $tournament = 1) {
      $query = '
        insert into machine set
          game_id = :gameId,
          tournamentEdition_id = :tournamentId,
          manufacturer_id = :manufacturerId,
          manufacturer = :manufacturer,
          game = :game
      ';
      $insert[':gameId'] = $this->id;
      $insert[':tournamentId'] = $tournament;
      $insert[':manufacturerId'] = $this->manufacturer_id;
      $insert[':manufacturer'] = $this->manufacturer;
      $insert[':game'] = $this->name;
      deNorm($dbh, 'machine');
      $sth = $dbh->prepare($query);
      if ($sth->execute($insert)) {
        return $dbh->lastInsertId();
      } else {
        return false;
      }
    }
    
    function remove($dbh) {
      $query = 'delete from machine where id = :id';
      $delete[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($delete)) ? true : false;
    }
    
    function setComment($dbh, $comment) {
      $query = 'update machine set comment = :comment where id = :id';
      $update[':comment'] = $comment;
      $update[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function setOwner($dbh, $owner = null) {
      $query = '
        update machine set
          owner_id = :ownerId,
          owner = :owner,
          ownerShortName = :ownerShortName
        where id = :id
      ';
      $update[':ownerId'] = ($owner) ? $owner->id : null;
      $update[':owner'] = ($owner) ? $owner->name : null;
      $update[':ownerShortName'] = ($owner) ? $owner->shortName : null;
      $update[':id'] = $this->machine_id;
      $sth = $dbh->prepare($query);
      return ($sth->execute($update)) ? true : false;
    }

    function removeOwner($dbh) {
      $this->setOwner($dbh);
    }

    function getAdminInfo($dbh, $type = 'all') {
      switch($type) {
        case 'all':
          $typeArray = array('game', 'shortName', 'manufacturer', 'ipdb', 'rules', 'type', 'usage');
          foreach($typeArray as $infoType) {
            $return .= $this->getAdminInfo($infoType);
          }
          return $return;
        break;
        case 'game':
          $owners = getOwners($dbh);
          $return = '
              '.$this->getLink().
              $this->getQR(true, true).
              $this->getQR(true, true, true).'
              <img src="'.__baseHref__.'/images/edit.png" class="icon right" onclick="adminGameEdit(this, true);" alt="Click to view/edit the game properties" title="Click to view/edit the game properties" id="'.$this->machine_id.'_edit">
              <div class="toolTip" id="'.$this->machine_id.'_editDiv">
                <img src="'.__baseHref__.'/images/cancel.png" class="icon right" onclick="adminGameEdit(this, false);" alt="Click to close this popup" title="Click to close this popup" id="'.$this->machine_id.'_editDivClose">
                <div id="'.$this->machine_id.'_ballsDiv" class="left inlineBlock">
                  <select id="'.$this->machine_id.'_balls" name="'.$this->machine_id.'_balls" onchange="adminGameBalls(this);" previous="'.$this->balls.'">
                    <option value="0">Balls...</option>
                    <option value="3"'.(($this->balls == '3') ? ' selected' : '').'>3 balls</option>
                    <option value="5"'.(($this->balls == '5') ? ' selected' : '').'>5 balls</option>
                  </select>
                  <span class="error errorSpan toolTip" id="'.$this->machine_id.'_ballsSpan"></span>
                </div>
                <div id="'.$this->machine_id.'_ownerDiv" class="left inlineBlock">
                  <select id="'.$this->machine_id.'_owner" name="'.$this->machine_id.'_owner" onchange="adminGameOwner(this);" previous="'.$this->owner_id.'">
                    <option value="0">Owner...</option>
          ';
          foreach ($owners as $owner) {
            $return .= '<option value="'.$owner->id.'"'.(($owner->id == $this->owner_id) ? ' selected' : '').'>'.$owner->shortName.'</option>';
          }
          $return .= '
                  </select>
                  <span class="error errorSpan toolTip" id="'.$this->machine_id.'_ownerSpan"></span>
                </div>
                <div id="'.$this->machine_id.'_extraBallsDiv" class="inlineBlock">
                  <label for="'.$this->machine_id.'_extraBalls">
                    <input type="checkbox" id="'.$this->machine_id.'_extraBalls" name="'.$this->machine_id.'_extraBalls" onclick="adminGameExtraBalls(this);" '.(($this->extraBalls) ? 'checked' : '').'>
                    Extraballs
                  </label>
                  <span class="error errorSpan toolTip" id="'.$this->machine_id.'_extraBallsSpan"></span>
                </div>
                <div id="'.$this->machine_id.'_onePlayerAllowedDiv" class="inlineBlock">
                  <label for="'.$this->machine_id.'_onePlayerAllowed">
                    <input type="checkbox" id="'.$this->machine_id.'_onePlayerAllowed" name="'.$this->machine_id.'_onePlayerAllowed" onclick="adminGameOnePlayerAllowed(this);" '.(($this->onePlayerAllowed) ? 'checked' : '').'>
                    One player allowed
                  </label>
                  <span class="error errorSpan toolTip" id="'.$this->machine_id.'_onePlayerAllowedSpan"></span>
                </div>
                <div id="'.$this->machine_id.'_commentDiv" class="clearboth">
                  <label for="'.$this->machine_id.'_comment">Comment:</label>
                  <input type="text" name="'.$this->machine_id.'_comment" id="'.$this->machine_id.'_comment" value="'.$this->comment.'" class="dbComment" onkeyup="enterClick(\''.$this->machine_id.'_commentSubmit\', event);">
                  <input type="button" id="'.$this->machine_id.'_commentSubmit" onclick="adminGameComment(this);" value="Change!">
                  <span class="error errorSpan toolTip" id="'.$this->machine_id.'_commentSubmitSpan"></span>
                </div>
              </div>
          ';
          return $return;
        break;
        case 'shortName':
          return $this->shortName;
        break;
        case 'manufacturer':
          return $this->manufacturer;
        break;
        case 'owner':
          return $this->machine_id.' ('.$this->getOwnerPopup().')'.$this->getOwnerInfo($dbh);
        break;
        case 'ipdb':
          return $this->getIpdbLink();
        break;
        case 'rules':
          return $this->getRulesLink();
        break;
        case 'type':
          return '
              <select id="'.$this->id.'_type" onchange="adminGameType(this);" previous="'.$this->gameType.'">
                <option value="0">Type...</option>
                <option value="modern"'.(($this->gameType == 'modern') ? ' selected' : '').'>Modern</option>
                <option value="classics"'.(($this->gameType == 'classics') ? ' selected' : '').'>Classics</option>
              </select>
              <span class="error errorSpan toolTip" id="'.$this->id.'_typeSpan"></span>
          ';
        break;
        case 'usage':
          return '
              <select id="'.$this->machine_id.'_usage" onchange="adminGameUsage(this);" previous="'.$this->tournamentDivision_id.'">
                <option value="0">Usage...</option>
                <option value="1"'.(($this->tournamentDivision_id == 1) ? ' selected' : '').'>Main</option>
                <option value="2"'.(($this->tournamentDivision_id == 2) ? ' selected' : '').'>Classics</option>
                <option value="3"'.(($this->tournamentDivision_id == 3) ? ' selected' : '').'>Team</option>
                <option value="13"'.(($this->tournamentDivision_id == 13) ? ' selected' : '').'>Side</option>
                <option value="14"'.(($this->tournamentDivision_id == 14) ? ' selected' : '').'>Recreational</option>
              </select>
              <span class="error errorSpan toolTip" id="'.$this->machine_id.'_usageSpan"></span>
              <img src="'.__baseHref__.'/images/cancel.png" class="icon right" onclick="adminGameDel(this);" alt="Click to remove the game from the tournament" title="Click to remove the game from the tournament" id="'.$this->machine_id.'_delete">
              <span class="error errorSpan toolTip" id="'.$this->machine_id.'_deleteSpan"></span>
          ';
        break;
      }
    }

    function getAllEntries ($dbh, $groupBy = false, $tournament = 1) {
      $query = '
        select
          qe.person_id as id,
          qs.id as qualScoreId,
          qe.id as qualEntryId,
          qs.place as place,
          qe.place as entryPlace,
          qs.points as points,
          qe.points as entryPoints,
          qs.score as score,
          qe.realPlayer_id as playerId,
          qe.firstName as firstName,
          qe.lastName as lastName,
          concat(ifnull(qe.firstName,""), " ", ifnull(qe.lastName,"")) as player,
          qe.country_id as countryId,
          qe.country as country,
          qs.machine_id as machineId,
          qs.gameAcronym as gameAcronym,
      ';
      $query .= ($groupBy) ? '
        max(qs.score) as maxScore,
        max(qs.points) as maxPoints,
        min(qs.place) as bestPlace,
      ' : '';
      $query .='
          qs.game as game
        from qualScore qs
          left join qualEntry qe
            on qs.qualEntry_id = qe.id
        where qs.machine_id = '.$this->machine_id.'
          and qe.tournamentDivision_id = '.$tournament.'
      ';
      $query .= ($groupBy) ? $groupBy : '';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('entry')) {
        $objs[] = $obj;
      }
      return $objs;
    }

    function getQR($link = true, $right = false, $info = false) {
      $img = ($info) ? 'print.png': 'qr.png';
      $title = ($info) ? 'Click to print game info' : 'Click to print QR code';
      $qr = '<img src="'.__baseHref__.'/images/'.$img.'" class="icon'.(($right) ? ' right': '').'" alt="'.$title.'" title="'.$title.'">';
      return ($link) ? '<a href="'.__baseHref__.'/mobile/gamePrinter.php?gameId='.$this->machine_id.(($info) ? '&info=1': '').'&autoPrint=true" target="_blank">'.$qr.'</a>' : $qr;
    }

  }
?>
