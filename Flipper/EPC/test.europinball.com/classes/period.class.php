<?php
  class period extends timeSlot {

    public $class = 'period';

    function addPlayers($dbh, $players, $tournament = 1) {
      foreach($players as $player) {
        $this->addPlayer($dbh, $player, $tournament);
      }
    }
    
    function addPlayer($dbh, $player, $tournament = 1) {
      if (!$player->volunteer) {
        $player->addVolunteer($dbh, $tournament);
      }
      return $player->addVolunteerPeriod($dbh, $this, $tournament);
    }
    
    function removePlayers($dbh, $players, $tournament = 1) {
      foreach($players as $player) {
        $this->removePlayer($dbh, $player, $tournament);
      }
    }

    function removePlayer($dbh, $player, $tournament = 1) {
      return $player->removeVolunteerPeriod($dbh, $this, $tournament);
    }
      
    function getNeed($dbh, $task) {
      if ($task) {
        $query = '
          select number 
            from volunteerNeed 
          where
            task_id = :taskId
            and period_id = :periodId 
        ';
        $select[':taskId'] = $task->id;
        $select[':periodId'] = $this->id;
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
    
    function setNeed($dbh, $task, $need = null) {
      if ($task) {
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
        $insert['taskId'] = $task->id;
        $insert['periodId'] = $this->id;
        $insert['need'] = $need;
        $sth = $dbh->prepare($query);
        $sth->execute($insert);
        return true;
      }
      return false;
    }

    function getAlloc($dbh, $task) {
      if ($task) {
        $query = '
          select count(*)
            from volunteerPeriod 
          where
            task_id = :taskId
            and period_id = :periodId 
        ';
        $select[':taskId'] = $task->id;
        $select[':periodId'] = $this->id;
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

  }
?>
