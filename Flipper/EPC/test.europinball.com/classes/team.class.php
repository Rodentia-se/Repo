<?php
  
  class team extends base {
    
    public $initials;
    public $player_ids = [];
    public $players = [];
    public $contactPlayer_id;
    public $registerPerson_id;
    public $contactPlayer_name;
    public $tournamentDivision;
    public $tournamentDivision_id;
    public $tournamentEdition;
    public $tournamentEdition_id;
    public $country_id;
    public $country;
    public $dateRegistered;
    public $class = 'team';
    
    public function __construct($data = null, $type = 'array') {
      switch ($type) {
        case 'json':
          if ($data) {
            $this->set(json_decode($json, true));
          }
        break;
        case 'array':
          if ($data) {
            $this->set($data);
          }
        break;
      }
    }
    
    public function set($data) {
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }
    }
    
    public function setCaptain($dbh, $player) {
      $query = 'update team
        set contactPlayer_id=:contactPlayer_id,
        contactPlayer_name=:contactPlayer_name
        where id = '.$this->id.'
      ';
      $update[':contactPlayer_id'] = $player->mainPlayerId;
      $update[':contactPlayer_name'] = $player->name;
      $sth = $dbh->prepare($query);
      if ($sth->execute($update)) {
        return true;
      } else {
        return false;
      }
    }
    
    function addPlayer($dbh, $player) {
      if ($player) {
        $this->removePlayer($dbh, $player);
        $query = '
          insert into teamPlayer set
            teamPlayer.name=:name,
            teamPlayer.team_id=:teamId,
            teamPlayer.player_id=(
              select pl.id from player pl
              where pl.person_id=:playerId
              and pl.tournamentDivision_id=:tournamentDivision_id
            )
        ';
        $insert[':name'] = $this->name.' member '.$player->initials;
        $insert[':teamId'] = $this->id;
        $insert[':playerId'] = $player->id;
        $insert[':tournamentDivision_id'] = '1';
        $sth = $dbh->prepare($query);
        $sth->execute($insert);
        return $dbh->lastInsertId();
      } else {
        return false;
      }
    }
    
    function addPlayers($dbh, $players) {
      if ($players) {
        foreach ($players as $player) {
          $this->addPlayer($dbh, $player);
        }
        return true;
      } else {
        return false;
      }
    }

    function removePlayer($dbh, $player) {
      $query = '
        delete teamPlayer from teamPlayer
        left join player
          on teamPlayer.player_id = player.id
        where teamPlayer.team_id=:teamId and player.person_id=:playerId
          and player.tournamentDivision_id=:tournamentDivision_id
      ';
      $delete[':teamId'] = $this->id;
      $delete[':playerId'] = $player->id;
      $delete[':tournamentDivision_id'] = '1';
      $sth = $dbh->prepare($query);
      $sth->execute($delete);
      $rowCount = $sth->rowCount();
      if (count($this->getMembers($dbh)) < 1) {
        $this->delete($dbh);
      }
      return $rowCount;
    }

    function removePlayers($dbh, $players = 'all') {
      if ($players == 'all') {
        $query = 'delete from teamPlayer where team_id=:teamId';
        $delete[':teamId'] = $this->id;
        $sth = $dbh->prepare($query);
        $sth->execute($delete);
        return $sth->rowCount();
      } else {
        foreach ($players as $player) {
          $this->removePlayer($dbh, $player);
        }
        return true;
      }
    }
    
    function getMembers($dbh) {
      $query = getPlayerSelect();
      $query .= '
        left join teamPlayer tp on tp.player_id = m.id
        where tp.team_id = '.$this->id;  
      if($sth = $dbh->query($query)){
        unset($this->players);
        while ($obj = $sth->fetchObject('player')) {
          $objs[] = $obj;
        }
      }
      if ($objs) {
        $this->players = $objs;
        return $objs;
      }
      return false;
    }
    
    function delete($dbh) {
      $this->removePlayers($dbh, 'all');
      $query = 'delete from team where id=:teamId';
      $delete[':teamId'] = $this->id;
      $sth = $dbh->prepare($query);
      return $sth->execute($delete);
      
    }
    
  }
?>