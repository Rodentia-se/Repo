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
        case 'qr':
          return $this->getQrLabel();
        break;
        case 'edit':
        default:
          $comboboxClass = $prefix.'machineCombobox'.$this->id;
          $editClass = $prefix.'machineEdit'.$this->id;
          $editDiv = new div($prefix.'MachineEditDiv');
            $editDiv->addH3((($title) ? $title : 'Edit machine: '.$this->shortName.' in '.$this->tournamentDivisionName), array('class' => 'entry-title'));
            $editDiv->addParagraph('Note: All changes below are INSTANT when you press enter, press a button or move away from the field.', NULL, 'italic');
            $ballsDiv = $editDiv->addDiv($prefix.'MachineBallsDiv');
              $ballsSpinner = $ballsDiv->addSpinner($prefix.'Balls'.$this->id, (($this->balls) ? $this->balls : 3), 'text', 'Number of balls', array('class' => $editClass));
              $ballsSpinner->name = 'balls';
            //$ballsDiv
            $extraBallsDiv = $editDiv->addDiv($prefix.'MachineExtraBallsDiv');
              $extraBallsDiv->addLabel('Extra balls', NULL, NULL, 'normal');
              $extraBallsBox = $extraBallsDiv->addCheckbox($prefix.'ExtraBalls'.$this->id, ($this->extraBalls), array('id' => $prefix.'extraBalls'.$this->id, 'class' => $editClass, 'label' => 'Allowed'));
              $extraBallsBox->name = 'extraBalls';
            //$extraBallsDiv
            $onePlayerAllowedDiv = $editDiv->addDiv($prefix.'MachineOnePlayerAllowedDiv');
              $onePlayerAllowedDiv->addLabel('Single player play', NULL, NULL, 'normal');
              $onePlayerAllowedBox = $onePlayerAllowedDiv->addCheckbox($prefix.'OnePlayerAllowed'.$this->id, ($this->onePlayerAllowed), array('id' => $prefix.'onePlayerAllowed'.$this->id, 'class' => $editClass, 'label' => 'Allowed'));
              $onePlayerAllowedBox->name = 'onePlayerAllowed';
            //$onePlayerAllowedDiv
            $owners = owners('all');
            $ownerDiv = $editDiv->addDiv($prefix.'OwnerDiv');
              $ownerSelectDiv = $editDiv->addDiv();
                $ownerSelect = $ownerSelectDiv->addContent($owners->getSelectObj($prefix.'OwnerSelect'.$this->id, $this->owner_id, 'Owner', array('class' => $comboboxClass)));
                  $ownerSelect->name = 'owner_id';
                  $ownerSelect->addCombobox();
                  $ownerSelect->addValueSpan('Owner ID:');
                //$ownerSelect
              //$ownerSelectDiv
            //$ownerDiv
            $editDiv->addScriptCode('
              $(".'.$comboboxClass.'").combobox()
              .change(function(){
                var el = this;
                $("body").addClass("modal");
                var combobox = document.getElementById(el.id + "_combobox");
                $(combobox).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setProp.php", {class: "'.get_class($this).'", id: '.$this->id.', prop: el.name, value: $(el).val()})
                .done(function(data) {
                  $("body").removeClass("modal");
                  $(combobox).tooltipster("update", data.reason).tooltipster("show");
                  if (data.valid) {
                    $(el).data("previous", $(el).val());
                  } else {
                    $(el).val($(el).data("previous"));
                  }
                  $(combobox).val($(el).children(":selected").text())
                });
              });
              $(".custom-combobox-input").tooltipster({
                theme: ".tooltipster-light",
                content: "Updating the database...",
                trigger: "custom",
                position: "right",
                offsetX: 38,
                timer: 3000
              });
              $(".'.$editClass.'").change(function(){
                var el = this;
                $("body").addClass("modal");
                var value = ($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val();
                $(el).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setProp.php", {class: "'.get_class($this).'", id: '.$this->id.', prop: el.name, value: value})
                .done(function(data) {
                  $("body").removeClass("modal");
                  $(el).tooltipster("update", data.reason).tooltipster("show");
                  if (data.valid) {
                    $(el).data("previous", (($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val()));
                  } else {
                    if ($(el).is(":checkbox")) {
                      el.checked = ($(el).data("previous"));
                    } else {
                      $(el).val($(el).data("previous"));
                    }
                  }
                });
              })
              .tooltipster({
                theme: ".tooltipster-light",
                content: "Updating the database...",
                position: "right",
                trigger: "custom",
                timer: 3000
              });
            ');
            $editDiv->addContent($this->getQrLabel())->addCss('margin-top: 20px;');
          //$editDiv
          return $editDiv;
        break;
      }
    }
    
    public function getQrLabel() {
      $div = new div();
      $qrDiv = $div->addDiv();
      $qrDiv->addClick('
        window.open("'.config::$baseHref.'/ajax/getObj.php?class=machine&type=qr&id='.$this->id.'&autoPrint=1&flags=1");
      ');
      $qrDiv->title = 'Click to print';
      $table = $qrDiv->addTable();
      $table->class = 'qrTable';
      $tr = $table->addTr();
      $td = $tr->addTd($this->manufacturerName);
      $td->class = 'qrLabelTd';
      $td->addBr();
      $td->addSpan($this->shortName, NULL, 'qrInitials');
      $td->addBr();
      $td->addSpan($this->id, NULL, 'qrId');
      $td->addBr();
      $division = division($this->tournamentDivision_id);
      $td->addSpan($division->divisionName);
      $qrTd = $tr->addTd();
      $qrTd->addImg($this->getLink('qr'));
      $qrTd->class = 'qrTd';
    	if(!isset($_REQUEST['autoPrint']) || !$_REQUEST['autoPrint']) {
        $div->addBr();
        $qrEditP = $div->addParagraph();
        $qrEditP->addLink(config::$baseHref.'/ajax/getObj.php?class=machine&type=qr&id='.$this->id.'&autoPrint=1"', 'Click here or above to print.', array('target' => '_blank'));
        $qrEditP = $div->addParagraph();
        $qrEditP->addLink(config::$baseHref.'/pages/qr.php?class=machine&division='.$division->id.'&autoPrint=1&', 'Click here to print all codes for '.$division->divisionName.' division.', array('target' => '_blank'));
        $qrEditP = $div->addParagraph();
        $qrEditP->addLink(config::$baseHref.'/pages/qr.php?class=machine&autoPrint=1&', 'Click here to print all codes for all divisions.', array('target' => '_blank'));
    	}
      return $div;
    }
    
    function calcPoints($save = TRUE, $calcPlaces = TRUE) {
      if ($calcPlaces) {
        $this->calcPlaces($save);
      }
      $scores = scores($this);
      $scores->filter('place', '0', '>');
      if ($scores && count($scores) > 0) {
        foreach ($scores as $score) {
//          $points = 100 * (1 - ($score->place - 0.5) / count($scores));
          $points = ((count($scores) + 0.5 - $score->place) * (config::$calcFixed / count($scores))) + (((1 - (($score->place - 0.5) / count($scores)) ^ 0.5) ^ config::$calcCoefficient) * config::$calcDynamic);
                    ((COUNT(C$2:C$65)+ 0.5  - C2          ) * ($B$2               / COUNT(C$2:C$65)))+ (((1-  ((C2            - 0.5) / COUNT(C$2:C$65))^ 0.5) ^ $B$4                    ) * $B$3)
          $score->points = $points;
          if ($save) {
            $score->save();
          }
        }
        return TRUE;
      }
      return FALSE;
    }
    
    public function calcPlaces($save = TRUE) {
      $scores = scores($this);
      $scores->filter('score', '0', '>');
      if ($scores && count($scores) > 0) {
        $scores->order('score', 'numeric', 'desc');
        $place = 0;
        foreach ($scores as $score) {
          $place++;
          if ($save) {
            $score->setPlace($place);
          } else {
            $score->place = $place;
          }
        }
        return TRUE;
      }
      return FALSE;
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