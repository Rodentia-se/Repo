<?php
  class timeSlot extends base {
    
    public $tournamenDivision_id;
    public $tournamenEdition_id;
    public $date;
    public $fullName;
    public $shortName;
    public $startTime;
    public $endTime;
    public $player_id;
    public $volunteer_id;
    public $class = 'timeSlot';
    public $players = array();
    public $potentialPlayers = array();
    
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
    
    function addPlayer($dbh, $player) {
      return false;
    }
    
    function removePlayers($dbh, $players) {
      if ($players) {
        foreach ($players as $player) {
          $this->removePlayer($dbh, $player);
        }
        return true;
      } else {
        return false;
      }
    }

    function removePlayer($dbh, $player) {
      return false;
    }

    function getPlayers($dbh) {
      return false;
    }
    
    function getNoOfPlayers($dbh, $prefered = false) {
      return false;
    }
    
    public function getRow($checked = false, $prefered = false, $disabled = false) {
      $content = '<tr id="'.$this->id.'_'.$this->class.'Tr">';
      $content .= ($this->class == 'task') ? '<td class="labelTd"><label>'.ucfirst($this->name).'</label></td>' : '';
      $content .= '<td class="checkboxTd"><input type="checkbox" id="'.$this->id.'_'.$this->class.'Checkbox" onchange="timeSlotChanged(this, \''.$this->class.'\', '.$this->id.');" class="'.$this->class.'Checkbox '.(($this->class == 'qualGroup') ? $this->tournamentDivision_id.'_' : '').$this->date.'" ';
      $content .= ($checked) ? ' checked ' : '';
      $content .= ($disabled) ? ' disabled ' : '';
      $content .= '>';
      $content .= ($this->class == 'qualGroup') ? '<input type="radio" id="'.$this->id.'_'.$this->class.'Radio" name="'.$this->class.'Div'.$this->tournamentDivision_id.'Radio" onchange="timeSlotPreferedChanged(this, '.$this->id.')" class="'.$this->class.'Radio '.$this->date.'" '.(($prefered) ? ' checked ' : '').(($disabled) ? ' disabled ' : '').'>' : '';
      $content .= '<span class="error errorSpan toolTip" id="'.$this->id.'_'.$this->class.'Span"></span>';
      $content .= ($this->comment) ? '<span class="italic">'.$this->comment.'</span>' : '';
      $content .= '</td>';
      $content .= ($this->class == 'period' || $this->class == 'qualGroup') ? '<td class="labelTd"><label>'.ucfirst($this->name).'</label></td>' : '';
      $content .= '</tr>';
      return $content;
    }
  }
?>