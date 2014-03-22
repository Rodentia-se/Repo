<?php

  class qualGroup extends timeSlot {
        
    public static $instances;
    public static $arrClass = 'qualGroups';

    public static $select = '
      select 
        o.id as id,
        concat(o.name, ": ", o.date, " ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as name,
        concat(o.name, ": ", o.date, " ", startTime, " - ", endTime) as fullName,
        concat(o.name, ": ", replace(replace(startTime, ":00", ""), ":00", ""), "-", replace(replace(endTime, ":00", ""), ":00", "")) as shortName,
        concat(o.date, " ", startTime, " - ", endTime) as dateName,
        concat(o.date, startTime) as sortName,
        o.name as acronym,
        o.date as date,
        o.startTime as startTime,
        o.endTime as endTime,
        o.tournamentDivision_id as tournamentDivision_id,
        o.comment as comment
      from qualGroup o
    ';
    
    public static $parents = array(
      'tournamentDivision' => 'division'
    );
    
    // @todo: Fix children
/*
    public static $children = array(
      'player' => 'qualGroup',
      'team' => 'qualGroup',
      'playerQualGroups' => array(
        'field' => 'qualGroup',
        'delete' => TRUE
      )
    );
*/
    
    public static $infoProps = array(
      'name' => 'acronym',
      'date',
      'start' => 'startTime',
      'end' => 'endTime'
    );
    
    public static $infoChildren = array(
      'players'
    );

    public function getChildrenTabs($division = 'main') {
      $division = getDivision($division);
      $tabs = new tabs(NULL, 'childrenTabs');
        foreach (static::$infoChildren as $childArrayClass) {
          $children = $childArrayClass($division, $this);
          if ($children && count($children) > 0) {
            $childrenDiv = $tabs->addDiv($childArrayClass.'Div');
            $childrenDiv->addContent($children->getTable());
          }
        }
      //}
      return $tabs;
    }
    
    public function getRegRow($array = FALSE) {
      $return = array(
        $this->getLink()
/*
        $this->getLink('shortName'),
        (is_object($this->city)) ? $this->city->getLink() : $this->cityName,
        (is_object($this->region)) ? $this->region->getLink() : $this->regionName,
        (is_object($this->country)) ? $this->country->name : $this->countryName,
        (is_object($this->country)) ? $this->country->getIcon() : $this->countryName,
        (($this->ifpaRank) ? $this->ifpaRank : (($this->getLink('ifpa')) ? 99000 : 100000)),
        str_replace('Unranked', 'Unr', $this->getLink('ifpa')),
        (($this->person) ? $this->person->getPhotoIcon() : ''),
        (($this->waiting) ? ((isId($this->waiting)) ? $this->waiting : '*'): ''),
        (($this->paid) ? 'Yes' : '')
*/
      );
      return ($array) ? $return : (object) $return;
    }
    
    public function getEdit($type = 'groupsAdmin', $title = NULL, $tournament = NULL, $prefix = NULL) {
      switch ($type) {
        case 'edit': 
        default:
          $div = new div($prefix.'qualGroupEditDiv_'.$this->id);
          $players = players($this->tournamentDivision);
          $groupPlayers = players($this);
          $headers = array('ID', 'Name', 'Action');
          foreach ($groupPlayers as $groupPlayer) {
            $delIcon = new img(config::$baseHref.'/images/cancel.png', 'Click to remove player', array('class' => 'icon delIcon'));
            $rows[] = array($groupPlayer->id, $groupPlayer->name, $delIcon);
          }
          $div->addH3('Players', array('class' => 'entry-title'));
          $p = $div->addParagraph('Qualification group '.$this->name);
          $p->addBr();
          $p->addContent($this->comment);
          $table = $div->addTable($rows, $headers);
          $table->addDatatables();
          $tr = new tr();
          $tr->addTd('Add player');
          $playerSelect = $players->getSelectObj($prefix.'qualGroupAddPlayer'.$this->id, NULL, FALSE);
          $playerSelect->addCombobox();
          $tr->addTd($playerSelect)->entities = FALSE;
          $addIcon = new img(config::$baseHref.'/images/add_icon.gif', 'Click to add player', array('class' => 'icon'));
          $td = $tr->addTd($addIcon);
          $td->entities = FALSE;
          $tr->type = 'tbody';
          $table->addContent($tr);
          $groupPlayerMailAddresses = $groupPlayers->getListOf('mailAddress');
          if ($groupPlayerMailAddresses) {
            $div->addH2('Email addresses', array('class' => 'entry-title'))->addCss('margin-top', '15px');
            $div->addParagraph('Note: Players that haven\'t registered their email address are not included. Click in the box to copy the addresses to your clipboard.', NULL, 'italic');
            $groupPlayerMailDiv = $div->addDiv('groupPlayerMailDiv');
              $groupPlayerMailDiv->addParagraph('Email addresses to the players in group '.$this->acronym);
              $groupPlayerMailDiv->addParagraph(implode(', ', $groupPlayerMailAddresses), $prefix.'groupPlayerMailAddresses', 'toCopy');
            //$groupPlayerMailDiv
          }
          $addIcon->addClick('
            var el = $("#'.$playerSelect->id.'");
            var combobox = document.getElementById("'.$playerSelect->id.'_combobox");
            $(combobox).tooltipster("update", "Updating the database...").tooltipster("show");
            $.post("'.config::$baseHref.'/ajax/setProp.php", {class: "player", id: $(el).val(), prop: "qualGroup_id", value: '.$this->id.'})
            .done(function(data) {
              $(combobox).tooltipster("update", data.reason).tooltipster("show");
              if (data.valid) {
                $("#'.$table->id.'").dataTable().fnAddData([
                  $(el).val(),
                  $(el).children(":selected").text(),
                  "<img class=\"icon\" title=\"Click to remove player\" id=\"'.$prefix.'qualGroupEditDiv'.$this->id.'_" + $(el).val() + "\" alt=\"Click to remove player\" src=\"'.config::$baseHref.'/images/cancel.png\">"
                ]);
                $("#'.$prefix.'qualGroupEditDiv'.$this->id.'_" + $(el).val()).click(function() {
                  var position = $("#'.$table->id.'").dataTable().fnGetPosition(this.parentNode);
                  var row = position[0];
                  var data = $("#'.$table->id.'").dataTable().fnGetData(row);
                  showMsg("Updating the database...");
                  $.post("'.config::$baseHref.'/ajax/setProp.php", {class: "player", id: data[0], prop: "qualGroup_id", value: 0})
                  .done(function(data) {
                    showMsg(data.reason);
                    if (data.valid) {
                      $("#'.$table->id.'").dataTable().fnDeleteRow(row);
                    }
                  });
                });                
                $(el).val(0);
                $(el).change();
              }
            });
          ');
          $div->addScriptCode('
          $(".delIcon").click(function() {
              var position = $("#'.$table->id.'").dataTable().fnGetPosition(this.parentNode);
              var row = position[0];
              var data = $("#'.$table->id.'").dataTable().fnGetData(row);
              showMsg("Updating the database...");
              $.post("'.config::$baseHref.'/ajax/setProp.php", {class: "player", id: data[0], prop: "qualGroup_id", value: 0})
              .done(function(data) {
                showMsg(data.reason);
                if (data.valid) {
                  $("#'.$table->id.'").dataTable().fnDeleteRow(row);
                }
              });
            });
            $("#'.$playerSelect->id.'_combobox").tooltipster({
              theme: ".tooltipster-light",
              content: "Updating the database...",
              trigger: "custom",
              position: "right",
              offsetX: 38,
              timer: 3000
            });
          ');
          return $div;
        break;
      }
    }
    
    public function getStandingsImg($htmlObj = TRUE) {
      $url = $this->getLink('standings');
      if ($url) {
        if ($htmlObj) {
          $return = new img($url);
        } else {
          $return = $url;
        }
        return $return;
      }
      return FALSE;
    }

    public function getMatchesImg($num = 1, $htmlObj = TRUE) {
      $url = $this->getLink('matches', $num);
      if ($url) {
        if ($htmlObj) {
          $return = new img($url);
        } else {
          $return = $url;
        }
        return $return;
      }
      return FALSE;
    }

    public function getLink($type = 'object', $anchor = TRUE, $thumbnail = FALSE, $preview = FALSE, $defaults = TRUE, $text = NULL) {
      switch ($type) {
        case 'standings':
          foreach (config::$photoExts as $ext) {
            if (file_exists(config::$baseDir.'/images/objects/qualGroup/standings/'.$this->id.'.'.$ext)) {
              $url = config::$baseHref.'/images/objects/qualGroup/standings/'.$this->id.'.'.$ext;
            }
          }
          return ($url && $anchor) ? '<a href="'.$url.'">'.(($text) ? $text : $this->name).'</a>' : $url;
        break;
        case 'matches'
          foreach (config::$photoExts as $ext) {
            if (file_exists(config::$baseDir.'/images/objects/qualGroup/matches/'.$this->id.'.'.$ext)) {
              $url = config::$baseHref.'/images/objects/qualGroup/matches/'.$this->id.'.'.$ext;
            }
          }
          return ($url && $anchor) ? '<a href="'.$url.'">'.(($text) ? $text : $this->name).'</a>' : $url;
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }

  }

?>