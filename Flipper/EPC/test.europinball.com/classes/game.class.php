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

    public static $infoProps = array(
      'name',
      'acronym',
      'manufacturer',
      'IPDB' => 'getIpdbLink',
      'Rules' => 'getRulesLink',
      'year'
    );
    
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
              'ipdb_id' => 'IPDB',
              'rules' => 'Ruls URL',
              'year' => 'Year'
            );
            foreach ($fields as $field => $label) {
              $editDivs[$field] = $editDiv->addDiv($prefix.'Game'.$field.'Div');
                $editDivs[$field]->addInput($field, $this->$field, 'text', $label, array('id' => $prefix.'Game'.$field, 'class' => (($field == 'year') ? $dateClass.' ' : '').$editClass));
              //}
            }
            $div = $editDiv->addDiv($editDiv.'GameManufacturer_idDiv');
              $sel = $div->addContent(manufacturers('all')->getSelectObj('manufacturer_id', $this->manufacturer_id, 'Manufacturer', array('class' => $comboboxClass)));
              $sel->id = $prefix.'GameManufacturer_id';
            //}
            $div = $editDiv->addDiv($prefix.'GameDivisionsDiv', 'divisionsDiv');
              $div->addLabel('Divisions', NULL, NULL, 'normal');
              foreach (config::$activeDivisions as $divisionType) {
                $division = division($tournament, $divisionType);
                $machine = machine($this, $division);
                $box[$divisionType] = $div->addCheckbox($divisionType, ($machine), array('id' => $prefix.'Game'.$divisionType, 'class' => $editClass));
              }
              $division = division($tournament, 'recreational');
              $box['recreational'] = $div->addCheckbox('recreational', ($machine), array('id' => $prefix.'Gamerecreational', 'class' => $editClass));
            //}
            $editDiv->addScriptCode('
              $(".'.$comboboxClass.'").combobox()
              .change(function(){
                var el = this;
                var combobox = document.getElementById(el.id + "_combobox");
                $(combobox).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {prop: el.id, value: $(el).val()})
                .done(function(data) {
                  $(combobox).tooltipster("update", data.reason).tooltipster("show");
                  if (data.valid) {
                    $(combobox).val($(el).children(":selected").text())
                    if (data.parents) {
                      $.each(data.parents, function(key, geo) {
                        if (!stop) {
                          if (data.parent_obj == geo) {
                            if (data.parent_id != $("#" + geo + "_id").val()) {
                              $("#'.$prefix.'" + geo + "_id").val(data.parent_id);
                              $("#'.$prefix.'" + geo + "_id").change();
                            }
                            var stop = true;
                          } else if ($("#'.$prefix.'" + geo + "_id").val() != 0) {
                            $("#'.$prefix.'" + geo + "_id").val(0);
                            $("#'.$prefix.'" + geo + "_id_combobox").val("");
                          }
                        }
                      });
                    }
                    $(el).data("previous", $(el).val());
                  } else {
                    $(el).val($(el).data("previous"));
                  }
                })
                .fail(function(jqHXR,status,error) {
                  $(combobox).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                })
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
                if (el.id == "'.$prefix.'shortName") {
                  $(el).val($(el).val().toUpperCase());
                } 
                var value = ($(el).is(":checkbox")) ? ((el.checked) ? 1 : 0) : $(el).val();
                var region_id = (this.id == "'.$prefix.'city") ? $("#'.$prefix.'region_id").val() : null;
                var country_id = (this.id == "'.$prefix.'city" || this.id == "'.$prefix.'region") ? $("#'.$prefix.'country_id").val() : null;
                var continent_id = (this.id == "'.$prefix.'city" || this.id == "'.$prefix.'region") ? $("#'.$prefix.'continent_id").val() : null;
                $(el).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setPersonProp.php", {prop: el.id, value: value, region_id: region_id, country_id: country_id, continent_id: continent_id})
                .done(function(data) {
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
                })
                .fail(function(jqHXR,status,error) {
                  $(el).tooltipster("update", "Fail: S: " + status + " E: " + error).tooltipster("show");
                })
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
                max: '.date('yy').',
                stop: function(event, ui) {
                  $(".'.$dateClass.'").change();
                })
              });
            ');
          //}
          return $profileDiv;
        break;
      }
    }


  }

?>