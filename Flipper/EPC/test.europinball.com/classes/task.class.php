<?php
  class task extends base {
    
    public $class = 'task';
    
    function addPlayers($dbh, $players, $tournament = 1) {
      foreach($players as $player) {
        $this->addPlayer($dbh, $player, $tournament);
      }
    }

    function addPlayer($dbh, $player, $tournament = 1) {
      if (!$player->volunteer) {
        $player->addVolunteer($dbh, $tournament);
      }
      return $player->addVolunteerTask($dbh, $this, $tournament);
    }
    
    function removePlayers($dbh, $players, $tournament = 1) {
      foreach($players as $player) {
        $this->removePlayer($dbh, $player, $tournament);
      }
    }

    function removePlayer($dbh, $player, $tournament = 1) {
      return $player->removeVolunteerTask($dbh, $this, $tournament);
    }
    
    function getPlayers($dbh, $tournament = 1) {
      return getVolunteers($dbh, $tournament);
    }
      
    function getVolunteers($dbh, $tournament = 1) {
      $query = getPlayerSelect();
      $query .= '
        left join volunteerTask vt
          on vt.volunteer_id = v.id
        where 
          vt.task_id = '.$this->id.'
          and m.tournamentEdition_id = '.$tournament.'
        order by p.firstName, p.lastName';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('player')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
    function getVolunteersByPeriod($dbh, $period, $tournament = 1) {
      $query = getPlayerSelect();
      $query .= '
        left join volunteerTask vt
          on vt.volunteer_id = v.id
        left join volunteerPeriod vp
          on vp.volunteer_id = v.id
        where 
          vt.task_id = '.$this->id.'
          and vp.period_id = '.$period->id.'
          and m.tournamentEdition_id = '.$tournament.'
        order by p.firstName, p.lastName';
      $sth = $dbh->query($query);
      while ($obj = $sth->fetchObject('player')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
    function getNeed($dbh, $period) {
      if ($period) {
        $query = '
          select number 
            from volunteerNeed 
          where
            task_id = :taskId
            and period_id = :periodId 
        ';
        $select[':taskId'] = $this->id;
        $select[':periodId'] = $period->id;
        $sth = $dbh->prepare($query);
        $sth->execute($select);
        $number = $sth->fetchColumn();
        if ($number) {
          return $number;
        } else {
          return 0;
        }
      } else {
        return false;
      }
    }
    
    function setNeed($dbh, $period, $need = null) {
      if ($period) {
        $query = '
          insert into volunteerNeed set
            task_id = :taskId,
            period_id = :periodId,
            number = :need
          on duplicate key update
            task_id = :taskId,
            period_id = :periodId,
            number = :need
        ';
        $insert['taskId'] = $this->id;
        $insert['periodId'] = $period->id;
        $insert['need'] = $need;
        $sth = $dbh->prepare($query);
        $sth->execute($insert);
        return true;
      }
      return false;
    }

    function getAlloc($dbh, $period = false) {
      $query = '
        select count(*) 
          from volunteerPeriod 
        where
          task_id = :taskId
      ';
      $select[':taskId'] = $this->id;
      if ($period) {
        $query .= ' and period_id = :periodId'; 
        $select[':periodId'] = $period->id;
      }
      $sth = $dbh->prepare($query);
      $sth->execute($select);
      $number = $sth->fetchColumn();
      if ($number) {
        return $number;
      } else {
        return 0;
      }
    }
    
    function getAllocVols($dbh, $period = false) {
      $query = '
        select 
          v.person_id as id,
          v.firstName as firstName,
          v.lastName as lastName,
          concat(ifnull(v.firstName, ""), ifnull(v.lastName, "")) as name,
          vp.period_id as periodId,
          vp.task_id as taskId,
          v.id as volunteer_id,
          ifnull(v.hours, 0) as hours,
          v.mobileNumber as mobileNumber,
          v.mailAddress as mailAddress,
          vp.length as length,
          vp.tournamentEdition_id as tournamentEdition_id
        from volunteerPeriod vp
        left join volunteer v
          on vp.volunteer_id = v.id
        where vp.task_id = :taskId
      ';
      $select[':taskId'] = $this->id;
      if ($period) {
        $query .= ' and period_id = :periodId'; 
        $select[':periodId'] = $period->id;
      }
      $sth = $dbh->prepare($query);
      $sth->execute($select);
      while ($obj = $sth->fetchObject('player')) {
        $objs[] = $obj;
      }
      return $objs;
    }
    
    function assign($dbh, $player, $period, $assign = true) {
      $query = '
        update volunteerPeriod vp 
          left join volunteer v
            on vp.volunteer_id = v.id
        set
          task_id = :taskId
        where
          v.person_id = :playerId
      ';
      $update[':taskId'] = ($assign) ? $this->id : null;
      $update[':playerId'] = $player->id;
      if ($period) {
        $query .= ' and vp.period_id = :periodId';
        $update['periodId'] = $period->id;
      }
      $sth = $dbh->prepare($query);
      $result = ($sth->execute($update)) ? true : false;
      deNorm($dbh, 'player');
      return $result;
    }
    
  }
?>