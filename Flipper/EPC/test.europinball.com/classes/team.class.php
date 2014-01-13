<?php

  class team extends player {
        
    public static $instances;
    public static $arrClass = 'teams';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.initials as shortName,
        o.national as national,
        o.contactPlayer_id as contactPlayer_id,
        o.city_id as city_id,
        o.region_id as region_id,
        o.parentRegion_id as parentRegion_id,
        o.country_id as country_id,
        o.parentCountry_id as parentCountry_id,
        o.continent_id as continent_id,
        o.tournamentDivision_id as tournamentDivision_id,
        o.tournamentEdition_id as tournamentEdition_id,
        o.dateRegistered as dateRegistered,
        o.registerPerson_id as registerPerson_id,
        o.comment as comment
      from team o
    ';
    
    public static $parents = array(
      'contactPlayer' => 'player',
      'registerPerson_id' => 'person',
      'tournamentEdition' => 'tournament',
      'tournamentDivision' => 'division',
      'city' => 'city',
      'region' => 'region',
      'parentRegion' => 'region',
      'country' => 'country',
      'parentCountry' => 'country',
      'continent' => 'continent'
    );

    // @todo: Fix children
/*
    public static $children = array(
      'player' => array(
        'field' => 'team',
        'delete' => TRUE
      ),
      'teamPlayer' => array(
        'table' => 'teamPlayer',
        'field' => 'player',
        'delete' => TRUE
      )
    );
*/
    
    public static $infoProps = array(
      'name',
      'tag',
      'city',
      'region',
      'country',
      'continent',
    );

    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      $persons = array('current', 'active', 'login', 'auth');
      $divisions = array_merge(array('current', 'active'), config::$divisions);
      if (is_string($data) && in_array($data, $persons)) {
        $person = person($data);
        if (!$person || !isId($person->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if ((isObj($data) && get_class($data) == 'tournament') || (is_string($data) && in_array($data, $divisions))) {
        $division = division($data, (($search !== config::NOSEARCH) ? $search : 'main'));
        if (!$division || !isId($division->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      }
      if ((isObj($data) && get_class($data) == 'person') || (is_string($search) && in_array($search, $divisions))) {
        $division = division((($search !== config::NOSEARCH) ? $search : 'main'));
        if (!$division || !isId($division->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if (is_string($search) && in_array($search, $persons)) {
        $person = person($search);
        if (!$person || !isId($person->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      }
      if (isObj($person)) {
        if (isId($person->id) && isObj($division) && isId($division->id)) {
          $where = team::$select.'
            left join teamPerson tp on tp.team_id = o.id
            where tp.person_id = :person_id
              and o.tournamentDivision_id = :division_id
          ';
          $values[':person_id'] = $person->id;
          $values[':division_id'] = $division->id;
          $obj = $this->db->select($query, $values, 'team');
          if ($obj) {
            $this->_set($obj);
          } else {
            $this->failed = TRUE;
            return FALSE;
          }
          if ($this->id) {
            static::$instances['ID'.$this->id] = $this;
            $this->populate($depth);
            return TRUE;
          } else {
            $this->failed = TRUE;
            return FALSE;
          }
        }
      }
      parent::__construct($data, $search, $depth);
    }

    public function getMemberInfo($tournament = NULL, $type = 'div', $asPlayers = TRUE) {
      $tournament = ($tournament) ? getTournament($tournament) : (($this->tournamentEdition) ? $this->tournamentEdition : getTournament());
      $division = division($tournament, 'main');
      if (isTournament($tournament)) {
        $members = ($asPlayers) ? players($this) : persons($this);
        if ($members && count($members) > 0) {
          $membersDiv = new div($this->id.'_'.get_class($this).'_teamMembersDiv');
          $membersDiv->addLabel('Members');
          $membersSpan = $membersDiv->addSpan(NULL, NULL, 'info');
          foreach($members as $member) {
            $membersSpan->addLink($member->getLink('object', FALSE), $member->name);
            $membersSpan->addBr();
          }
          return ($type == 'div') ? $membersDiv : $members;
        }
      }
      return FALSE;
    }
    
    public function getPhotoIcon($icon = NULL) {
      $icon = ($icon) ? $icon : $this->getPhoto(FALSE, TRUE, FALSE);
      return parent::getPhotoIcon($icon);
    }

    public function getPhoto($defaults = TRUE, $thumbnail = FALSE, $anchor = FALSE) {
      if ($this->national && $this->country_id) {
        if (isGeo($this->country)) {
          $country = $this->country;
        } else {
          $country = country($this->country_id);
        }
      }
      if (isGeo($country)) {
        return $country->getPhoto($defaults, $thumbnail, $anchor);
      } else {
        return parent::getPhoto($defaults, $thumbnail, $anchor);
      }
    }
    
    public static function validateName($name, $obj = FALSE) {
      if (!$name) {
        return validated(TRUE, 'Nothing to validate.', $obj);
      }
      if (!preg_match('/^[a-zA-ZåäöÅÄÖüÛïÎëÊÿŸçßéÉæøÆØáÁóÓàÀČčŁłĳŠšŮ0-9 \-_#\$]{3,32}$/', $name)) {
        return validated(FALSE, 'The name must be at least three character and can only include a-Z, A-Z, most of ÜÅÄÖ and similar, 0-9, spaces, #, $, dashes and underscores.', $obj);
      } else {
        $team = team('name', $name);
        if ($team) {
          $currentTeam = team('current');
          if ($team->id == $currentTeam->id) {
            return validated(TRUE, 'That name does already belong to your team, you didn\'t change it.', $obj);
          } else {
            return validated(FALSE, 'That name is already taken.', $obj);
          }
        }
      }
      return validated(TRUE, 'That name is up for grabs.', $obj);
    }

    public function getEdit($type = 'edit', $title = NULL, $tournament = NULL, $prefix = NULL) {
      $tournament = getTournament($tournament);
      switch ($type) {
        case 'members':
        case 'member':
          $members = players($this, $tournament);
          debug($members);
        break;
        case 'admin':
          $adminDiv = new div();
          if ($title) {
            $adminDiv->addH2('Admin options', array('class' => 'entry-title'));
          }
          return $adminDiv;
          break;
          $adminDiv->addH2('Waiting list', array('class' => 'entry-title'));
          $adminDiv->addParagraph('Click the checkboxes to except players from the waiting list for each division,', NULL, 'italic');
          foreach (config::$activeSingleDivisions as $divisionType) {
            $division = division($tournament, $divisionType);
            $player = ($this->id) ? player($this, $division) : NULL;
            $div = $adminDiv->addDiv(NULL, 'divisionsDiv'); 
            $checkbox = $div->addCheckbox('adminNoWaiting'.$divisionType, $player->noWaiting, array('class' => 'nowaiting'));
            $checkbox->label = $division->divisionName;
            $checkbox->disabled = !$player;
            $checkbox->data_playerid = $player->id;
            $checkbox->addTooltip('');
            $div->addLabel('Current place:');
            $div->addSpan((($player) ? (($player->noWaiting) ? 'Excepted from list' : (($player->waiting) ? $player->waiting : 'Not waiting' )) : 'Not registered for division'));
          }
          $adminDiv->addChange('
            var el = this;
            $(el).tooltipster("update", "Updating waiting exception...").tooltipster("show");
            $("body").addClass("modal");
            $.post("'.config::$baseHref.'/ajax/setProp.php", {class: "player", id: $(el).data("playerid"), prop: "noWaiting", value: ((el.checked) ? 1 : 0)})
            .done(function(data) {
              $(el).tooltipster("update", data.reason).tooltipster("show");
              if (data.valid) {
                $(el).data("previous", ((el.checked) ? 1 : 0));
              } else {
                el.checked = ($(el).data("previous"));
              }
              $("body").removeClass("modal");
            });
          ', '.nowaiting');
          return $adminDiv;
        break;
        case 'photo':
          return $this->getPhotoEdit($prefix);
        break;
        case 'profile':
        case 'edit':
        case 'player':
        case 'team':
        default:
          $prefix = $prefix.'Team';
          $comboboxClass = 'combobox';
          $editClass = 'edit';
          $dateClass = 'date';
          $profileDiv = new div($prefix.'EditDiv');
            $profileDiv->addH2((($title) ? $title : 'Edit profile'), array('class' => 'entry-title'));
            $profileDiv->addParagraph('Note: All changes below are INSTANT when you press enter or move away from the field.', NULL, 'italic');
            if ($player->waiting) {
              $profileDiv->addParagraph($this->name.' is on the WAITING LIST for this tournament, and we will be contacted if a participation sport becomes available (make sure the contact information below is correct).');
            }
            $fields = array(
              'name' => 'Name',
              'shortName' => 'Tag'
            );
            foreach ($fields as $field => $label) {
              $editDivs[$field] = $profileDiv->addDiv($prefix.$field.'Div');
                $editDivs[$field]->addInput($field, $this->$field, 'text', $label, array('id' => $prefix.$field, 'class' => $editClass));
              //}
            }
            if (!$this->national) {
              foreach (array('city' => 'cities', 'region' => 'regions') as $geo => $geoArr) {
                $sel = $div->addDoublebox($geoArr('all')->getSelectObj($geo.'_id', $this->{$geo.'_id'}, ucfirst($geo), array('class' => $comboboxClass)), FALSE, $geo);
                //}
              }
            }
            foreach (array('country' => 'countries', 'continent' => 'continents') as $geo => $geoArr) {
              if (!$this->national || $geo == 'country') {
                $div = $profileDiv->addDiv($prefix.$geo.'_idDiv');
                  $sel = $div->addContent($geoArr('all')->getSelectObj($geo.'_id', $this->{$geo.'_id'}, ucfirst($geo), array('class' => $comboboxClass)));
                //}
              }
            } 
            $profileDiv->addScriptCode('
              $(".'.$comboboxClass.'").combobox()
              .change(function(){
                var el = this;
                var combobox = document.getElementById(el.id + "_combobox");
                $(combobox).tooltipster("update", "Updating the database...").tooltipster("show");
                $.post("'.config::$baseHref.'/ajax/setProp.php", {class: '.get_class($this).', id: '.$this->id.', prop: el.id, value: $(el).val()})
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
                $.post("'.config::$baseHref.'/ajax/setProp.php", {class: '.get_class($this).', id: '.$this->id.', prop: el.id, value: value, region_id: region_id, country_id: country_id, continent_id: continent_id})
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
            ');
          //}
          return $profileDiv;
        break;
      }
    }

  }

?>