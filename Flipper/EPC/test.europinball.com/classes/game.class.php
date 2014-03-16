<?php

  class game extends base {
        
    public static $instances;
    public static $arrClass = 'games';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.acronym as acronym,
        o.acronym as shortName,
        o.manufacturer_id as manufacturer_id,
        o.game_ipdb_id as ipdb,
        o.game_link_rulesheet as rules,
        o.game_year_released as year
      from game o
    ';
    
    public static $parents = array(
      'manufacturer' => 'manufacturer'
    );
    
    // @todo: Fix children
/*
    public static $children = array(
      'machine' => 'game'
    );
*/

    public static $cols = array(
      'game_ipdb_id' => 'ipdb',
      'game_link_rulesheet' => 'rules',
      'game_year_released' => 'year'
    );
    
    public static $infoProps = array(
      'name',
      'acronym',
      'manufacturer',
      'IPDB' => 'getIpdbLink',
      'Rules' => 'getRulesLink',
      'year'
    );
    
    public static $authorized = 'admin';
    
    public function getRegRow($array = FALSE) {
      // @todo: Handle custom headers
      // @todo: Change to object
      $return = array(
        $this->getLink(),
        $this->getLink('shortName'),
        (is_object($this->manufacturer)) ? (($this->manufacturer->getLink()) ? $this->manufacturer->getLink() : $this->manufacturer->name) : $this->manufacturerName,
        ($this->ipdb) ? $this->getLink('ipdb') : '',
        ($this->rules) ? $this->getLink('rules') : '',
        $this->year
      );
      return ($array) ? $return : (object) $return;
    }

    public function getIpdbLink() {
      return $this->getLink('ipdb');
    }

    public function getRulesLink() {
      return $this->getLink('rules');
    }

    public function getLink($type = 'object', $anchor = true, $thumbnail = false, $preview = false, $defaults = true) {
      switch ($type) {
        case 'ipdb':
          if ($this->ipdb) {
            $url = 'http://www.ipdb.org/machine.cgi?id='.$this->ipdb;
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_blank">'.$this->ipdb.'</a>' : $url;
        break;
        case 'rules':
          if ($this->rules) {
            $url = $this->rules;
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_blank"><img src="'.config::$baseHref.'/images/textbook_icon.png" alt="Rules" title="Rules" class="icon"></a>' : $url;
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }

    public function getEdit($type = 'edit', $title = NULL, $tournament = NULL, $prefix = NULL) {
      $tournament = getTournament($tournament);
      switch ($type) {
        case 'edit':
        default:
          $comboboxClass = 'combobox';
          $editClass = 'edit';
          $dateClass = 'yearSpinner';
          $editDiv = new div($prefix.'GameEditDiv');
            $editDiv->addH2((($title) ? $title : 'Edit game'), array('class' => 'entry-title'));
            $editDiv->addParagraph('Note: All changes below are INSTANT when you press enter or move away from the field.', NULL, 'italic');
            $fields = array(
              'name' => 'Name',
              'acronym' => 'Acronym',
              'ipdb' => 'IPDB',
              'rules' => 'Rules URL',
              'year' => 'Year'
            );
            foreach ($fields as $field => $label) {
              $editDivs[$field] = $editDiv->addDiv($prefix.'Game'.$field.'Div');
                $editDivs[$field]->addInput($field, $this->$field, 'text', $label, array('id' => $prefix.'Game'.$field, 'class' => (($field == 'year') ? $dateClass.' ' : '').$editClass));
                if ($field == 'rules') {
                  $gameRulesSpan = $editDivs[$field]->addSpan(NULL, $prefix.'GameRulesSpan');
                  if ($this->rules) {
                    $gameRulesLink = $gameRulesSpan->addLink($this->rules, 'Show rules', array('target' => '_blank', 'class' => 'buttonLink'));
                  }
                }
              //}
            }
            $div = $editDiv->addDiv($prefix.'GameManufacturer_idDiv');
              $sel = $div->addContent(manufacturers('all')->getSelectObj('manufacturer_id', $this->manufacturer_id, 'Manufacturer', array('class' => $comboboxClass)));
              $sel->id = $prefix.'GameManufacturer_id';
            //}
            $div = $editDiv->addDiv($prefix.'GameDivisionsDiv', 'divisionsDiv');
              $div->addLabel('Divisions', NULL, NULL, 'normal');
              $machineEditdiv = new div();
              $machineEditTabs = $machineEditdiv->addTabs();
              foreach (array_merge(config::$activeDivisions, array('recreational')) as $divisionType) {
                $division = division($tournament, $divisionType);
                $machines = machines($this, $division);
                $box[$divisionType] = $div->addCheckbox($divisionType, ($machines && count($machines) > 0), array('id' => $prefix.'Game'.$divisionType, 'class' => $editClass, 'data-tab' => $tab));
                if ($machines && count($machines) > 0) {
                  $machineEditTabs->addAjaxTab(config::$baseHref.'/ajax/getObj.php?class=machine&type=edit&id='.$machines[0]->id, ucfirst($divisionType));
                } else {
                  $machineEditTabs->addDiv()->dataTitle = $division->divisionName;
                }
                $tab++;
              }
              $editDiv->addContent($machineEditdiv);
            //}
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
                    if (el.name == "rules") {
                      $("#'.$gameRulesLink->id.'").attr("href", $(el).val());
                    }
                  } else {
                    if ($(el).is(":checkbox")) {
                      el.checked = ($(el).data("previous"));
                    } else {
                      $(el).val($(el).data("previous"));
                    }
                  }
                  if ($(el).is(":checkbox")) {
                    $("#'.$machineEditTabs->id.'").tabs("option", "active", $(el).data("tab"));
                    $("#'.$machineEditTabs->id.'").find("ul>li a").eq($("#'.$machineEditTabs->id.'").tabs("option", "active")).attr("href", "'.config::$baseHref.'/ajax/getObj.php?class=machine&type=edit&id=" + data.id);
                    $("#'.$machineEditTabs->id.'").tabs("load", $(el).data("tab"));
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
              $(".'.$dateClass.'").spinner({
                min: 1920,
                max: '.date('Y').',
                stop: function(event, ui) {
                  $(".'.$dateClass.'").change();
                }
              });
              $(".buttonLink").button();
            ');
          //}
          return $editDiv;
        break;
      }
    }
    
    function addDivision($division = NULL) {
      $division = division($division);
      if (isDivision($division)) {
        $tournament = tournament($division->tournamentEdition_id);
        $machine = machine($this, $division);
        if ($machine) {
          return $machine->id;
        } else {
          $machine = new machine();
          $machine->game_id = $this->id;
          $machine->game = $this->name;
          $machine->gameAcronym = $this->shortName;
          $machine->tournamentEdition_id = $division->tournamentEdition_id;
          $machine->tournamentDivision_id = $division->id;
          $machine->name = $tournament->name.', '.$division->divisionName.': '.$this->shortName;
          $machine->manufacturer_id = $this->manufacturer_id;
          $machine->manufacturer = $this->manufacturerName;
          return $machine->save();
        }
      }
      return FALSE;
    }

  }

?>