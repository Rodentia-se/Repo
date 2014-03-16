<?php

  class machine extends base {
        
    public static $instances;
    public static $arrClass = 'machines';

    public static $select = '
      select 
        o.id as id,
        g.id as game_id,
        g.name as name,
        g.name as fullName,
        g.acronym as acronym,
        g.acronym as shortName,
        g.manufacturer_id as manufacturer_id,
        g.game_ipdb_id as ipdb,
        g.game_link_rulesheet as rules,
        g.game_year_released as year,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.gameType as type,
        o.balls as balls,
        o.extraBalls as extraBalls,
        o.onePlayerAllowed as onePlayerAllowed,
        o.owner_id as owner_id,
        o.paid as paid,
        o.comment as comment
      from machine o
      left join game g
        on o.game_id = g.id
    ';
    
    public static $parents = array(
      'game' => 'game',
      'manufacturer' => 'manufacturer',
      'tournamentDivision' => 'division',
      'tournamentEdition' => 'tournament',
      'owner' => 'owner'
    );

    // @todo: Fix children
/*
    public static $children = array(
      'matchScore' => 'machine',
      'set' => 'machine',
      'score' => 'machine'
    );
*/

    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      if (isTournament($data) || in_array($data, array('active', 'current'))) {
        $data = tournament($data);
      } else if (isDivision($data) || in_array($data, config::$divisions)) {
        $data = division($data);
      }
      if (isTournament($search) || in_array($search, array('active', 'current'))) {
        $search = tournament($search);
      } else if (isDivision($search) || in_array($search, config::$divisions)) {
        $search = division($search);
      }
      if (isTournament($depth) || in_array($depth, array('active', 'current'))) {
        $depth = tournament($depth);
      } else if (isDivision($depth) || in_array($depth, config::$divisions)) {
        $depth = division($depth);
      }
      parent::__construct($data, $search, $depth);
    }
    
    public function getTr($headers = NULL) {
      // @todo: Handle custom headers
      $cells = $this->getRegRow(TRUE);
      $tr = new tr();
      foreach ($cells as $cell) {
        $tr->addTd($cell)->escape = FALSE;
      }
      return $tr;
    }

    public function getRegRow($array = FALSE) {
      // @todo: Handle custom headers
      // @todo: Change to object
      $return = array(
        $this->getLink(),
        (($this->game) ? $this->game->getPhotoIcon() : ''),
        $this->getLink('shortName'),
        (is_object($this->manufacturer)) ? (($this->manufacturer->getLink()) ? $this->manufacturer->getLink() : $this->manufacturer->name) : $this->manufacturerName,
        (is_object($this->owner)) ? (($this->owner->getLink()) ? $this->owner->getLink() : $this->owner->name) : $this->ownerName,
        ($this->ipdb) ? $this->getLink('ipdb') : '',
        ($this->rules) ? $this->getLink('rules') : '',
        $this->year
      );
      return ($array) ? $return : (object) $return;
    }

    public function getEdit($type = 'edit', $title = NULL, $tournament = NULL, $prefix = NULL) {
      $tournament = getTournament($tournament);
      switch ($type) {
        case 'edit':
        default:
          $editClass = $prefix.'machineEdit';
          $editDiv = new div($prefix.'MachineEditDiv');
            $editDiv->addH3((($title) ? $title : 'Edit machine'), array('class' => 'entry-title'));
            $editDiv->addParagraph('Note: All changes below are INSTANT when you press enter, press a button or move away from the field.', NULL, 'italic');
            $ballsDiv = $editDiv->addDiv($prefix.'MachineBallsDiv');
              $ballsSpinner = $ballsDiv->addSpinner($prefix.'MachineBallsDiv'.$this->id, (($this->balls) ? $this->balls : 3), 'text', 'Number of balls', array('class' => $editClass));
            $extraBallsDiv = $editDiv->addDiv($prefix.'MachineExtraBallsDiv');
              $extraBallsDiv->addLabel('', NULL, NULL, 'normal');
              $extraBallsBox = $extraBallsDiv->addCheckbox('Extra balls allowed', ($this->extraBalls), array('id' => $prefix.'extraBalls'.$this->id, 'class' => $editClass));
          return $editDiv;
        break;
      }
    }

    public function getLink($type = 'object', $anchor = true, $thumbnail = false, $preview = false, $defaults = true) {
      switch ($type) {
        case 'ipdb':
        case 'rules':
          return $this->game->getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }

  }

?>