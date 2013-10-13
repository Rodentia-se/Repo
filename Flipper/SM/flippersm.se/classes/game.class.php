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
    public $class = 'game';
   
    function getIpdbLink($href = true) {
      if ($this->ipdb_id) {
        $url = 'http://ipdb.org/machine.cgi?id='.$this->ipdb_id;
        return ($href) ? '<a href="'.$url.'" target="_blank">'.$this->ipdb_id.'</a>' : $url;
      } else {
        return false;
      }
    }
    
    function getRulesLink($href = true) {
      if ($this->ipdb_id) {
        return ($href) ? '<a href="'.$this->rules.'" target="_blank">Rules</a>' : $this->rules;
      } else {
        return false;
      }
    }
    
    function setGameType($dbh, $type) {
      $query = 'update machine set gameType = :type where game_id = :id';
      $update[':type'] = $type;
      $update[':id'] = $this->id;
      $sth = $dbh->prepare($query);
      deNorm($dbh, 'machine');
      return ($sth->execute($update)) ? true : false;
    }

    function setGameUsage($dbh, $division = 1) {
      $query = 'update machine set tournamentDivision_id = :division where id = :id';
      $update[':division'] = $division;
      $update[':id'] = $this->id;
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
    
    function getQR($link = true, $right = false, $info = false) {
      $img = ($info) ? 'print.png': 'qr.png';
      $title = ($info) ? 'Click to print game info' : 'Click to print QR code';
      $qr = '<img src="'.__baseHref__.'/images/'.$img.'.png" class="icon'.(($right) ? ' right': '').'" alt="'.$title.'" title="'.$title.'">';
      return ($link) ? '<a href="'.__baseHref__.'/mobile/gamePrinter.php?gameId='.$this->machine_id.(($info) ? '&info=1': '').'&autoPrint=true" target="_blank">'.$qr.'</a>' : $qr;
    }

    function remove($dbh) {
      $query = 'delete from machine where id = :id';
      $delete[':id'] = $this->id;
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
        and qe.tournamentDivision_id = '.$tournament.' ';
      $query .= ($groupBy) ? $groupBy : '';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('entry')) {
        $objs[] = $obj;
      }
      return $objs;
    }

  }
?>
