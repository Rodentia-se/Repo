<?php

  class division extends base {
    
    public static $instances;
    public static $arrClass = 'divisions';

    public static $table = 'tournamentDivision';

    public static $select = '
      select 
        o.id as id,
        d.id as division_id,
        d.name as divisionName,
        concat(substring_index(o.name, " ", 1), ", ", right(o.name, 4)) as name,
        o.name as fullName,
        d.shortName as shortName,
        d.acronym as acronym,
        d.shortName as type,
        d.main as main,
        d.team as team,
        d.national as national,
        if(ifnull(d.national, 0) = 1 and ifnull(d.team, 0) = 1, 1, 0) as nationalTeam,
        d.secondary as secondary,
        d.side as side,
        d.recreational as recreational,
        d.modern as modern,
        d.classics as classics,
        d.eighties as eighties,
        d.youth as youth,
        d.teamMembers as teamMembers,
        o.wpprPercentage as wpprPercantage,
        o.includeInSTats as includeInStats,
        o.tournamentEdition_id as tournamentEdition_id
      from tournamentDivision o 
      left join division d
        on o.division_id = d.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament'
    );
    
    // @todo: Fix children
/*
    public static $children = array(
      'machine' => array(
        'field' => 'tournamentDivision',
        'delete' => TRUE
      ),
      'match' => array(
        'field' => 'tournamentDivision',
        'delete' => true
      ),
      'player' => array(
        'field' => 'tournamentDivision', 
        'delete' => true
      ),
      'team' => array(
        'field' => 'tournamentDivision',
        'delete' => true
      ),
      '´qualGroup' => array(
        'field' => 'tournamentDivision',
        'delete' => true
      ),
      'entry' => 'tournamentDivision',
      'score' => 'tournamentDivision'
    );
*/
    
    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      $aliases = array('current', 'active');
      $divisions = array_merge($aliases, config::$divisions);
      if (is_string($data) && in_array($data, $aliases)) {
        $data = tournament($data);
        $search = ($search) ? $search : 'main';
        if (!$data || !isId($data->id)) {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if (is_string($data) && in_array($data, config::$activeDivisions)) {
        if (config::${$data.'Division'}) {
          $data = config::${$data.'Division'};
          $search = config::NOSEARCH;
        } else {
          $this->failed = TRUE;
          return FALSE;
        }
      } else if (is_string($data) && in_array($data, config::$divisions)) {
        $data = array($data => 1);
        $search = TRUE;
      }
      if (isTournament($data)) {
        $search = (is_string($search) && in_array($search, config::$divisions)) ? $search : 'main';
        $data = array(
          'tournamentEdition_id' => $data->id, 
          'd.'.$search => 1
        );
      }
      if (isObj($data) && $data->tournamentDivision_id) {
        $data = $data->tournamentDivision_id;
      }
      parent::__construct($data, $search, $depth);
    }

    public function isActive() {
      return in_array($this->id, config::$activeDivisions);
    }
    
    public function calcPlaces() {
      $machines = machines($this);
      $entries = entries($this);
      if ($machines && count($machines) > 0 && $entries && count($entries) > 0) {
        $machines->calcPoints();
        $entries->calcPlaces();
      }
      return FALSE;
    }
    
    public function calcWaiting($number = NULL) {
      $query = '
        update player 
          right join (
            select @rownum := @rownum +1 seq, 
              id AS pid, 
              ifnull(waiting, 0)
            from player, 
              (SELECT @rownum :=0)r
            where player.tournamentDivision_id = '.$this->id.'
             order by ifnull(player.noWaiting, 0) desc, player.id asc
            ) AS players
            ON players.pid = player.id
          set player.waiting = if(players.seq > '.(($number) ? $number : config::$participationLimit[$this->type]).', players.seq - '.(($number) ? $number : config::$participationLimit[$this->type]).', NULL)
      ';
      $return = $this->db->update($query);
      if ($return) {
        $query = 'select max(waiting) from player where tournamentDivision_id = '.$this->id;
        return $this->db->getValue($query);
      } else {
        return FALSE;
      }
    }
    
    public function getStandings() {
      if ($this->id == 15) {
        $qualGroups = qualGroups($this);
        if ($qualGroups && count($qualGroups) > 0) {
          $div = new div();
          $div->addH2('Qualification group standings')->class = 'entry-title';
          $tabs = $div->addTabs();
          foreach($qualGroups as $qualGroup) {
            $qualDiv = $tabs->addDiv($qualGroup->acronym);
            $qualDiv->addContent($qualGroup->getStandings());
          }
          return $div;
        }
      } else if ($this->id == 16) {  // TODO: Remove EPC 2014 specifics
        if (!file_exists(config::$baseDir.'/logs/calcPlaces_div'.$this->id.'.lock')) {
          $fh = fopen(config::$baseDir.'/logs/calcPlaces_div'.$this->id.'.lock', 'w');
          fwrite($fh, 'Place calculation has been started');
          fclose($fh);
          $this->calcPlaces();
        }
        $div = new div();
          $headers = array('Order', 'Place', 'Name', 'Photo', 'Country sort', 'Country', 'Games', 'Points');
          $reloadP = $div->addParagraph($reloadP);
          $reloadButton = $reloadP->addButton('Reload the table', $this->shortName.'_reloadButton', array('class' => 'reloadButton'));
          $table = $div->addTable(NULL, $headers, $this->shortName.'Table', 'resultsTable');
          $reloadButton->addClick('
            $("#'.$table->id.'").dataTable().fnReloadAjax("'.config::$baseHref.'/ajax/getObj.php?class=players&type=results&data=division&data_id='.$this->id.'");
          ');
          $div->addScriptCode('
            $(document).ready(function() {
              $("#'.$table->id.'").dataTable({
                "bProcessing": true,
                "bDestroy": true,
                "bJQueryUI": true,
                "bAutoWidth": false,
            	  "sPaginationType": "full_numbers",
                "aoColumnDefs": [
                {"aDataSort": [ 0 ], "aTargets": [ 1 ] },
                {"aDataSort": [ 4 ], "aTargets": [ 5 ] },
                  {"bVisible": false, "aTargets": [ 0, 4 ] },
                  {"sClass": "icon", "aTargets": [ 3, 5 ] }
                ],
                "fnDrawCallback": function() {
                  $(".photoPopup").each(function() {
                    $(this).dialog({
                      autoOpen: false,
                      modal: true, 
                      width: "auto",
                      height: "auto"
                    });
                  });
                  $("#'.$table->id.'").css("width", "");
                  $(".photoIcon").click(function() {
                    var photoDiv = $(this).data("photodiv");
                    $("#" + photoDiv).dialog("open");
                    $(document).on("click", ".ui-widget-overlay", function() {
                      $("#" + photoDiv).dialog("close");
                    });
                  });
                  $("#mainContent").removeClass("modal");
                  return true;
                },
                "oLanguage": {
                  "sProcessing": "<img src=\"'.config::$baseHref.'/images/ajax-loader-white.gif\" alt=\"Loading data...\">"
                },
                "iDisplayLength": -1,
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
              });
              $("#'.$table->id.'").dataTable().fnReloadAjax("'.config::$baseHref.'/ajax/getObj.php?class=players&type=results&data=division&data_id='.$this->id.'");
              $(":button").button();
            });
          ');
        //Div
        return $div;
      } else if ($this->id == 17) {  // TODO: Remove EPC 2014 specifics
        $div = new div();
        $qualDiv = $div->addDiv();
        $group1div = $qualDiv->addDiv();
        $group1div->addCss('float', 'left');
        $group1div->addCss('position', 'relative');
        $qualP = $group1div->addParagraph();
        $qualP->addH2('Group 1')->class = 'entry-title';
        $qualP->addSpan('1: Spain (63p)');
        $qualP->addBr();
        $qualP->addSpan('1: Finland (43p)');
        $qualP->addBr();
        $qualP->addSpan('1: Germany (41p)');
        $qualP->addBr();
        $qualP->addSpan('1: Sweden (38p)');
        $group2div = $qualDiv->addDiv();
        $group2div->addCss('position', 'relative');
        $qualP = $group2div->addParagraph();
        $qualP->addH2('Group 2')->class = 'entry-title';
        $qualP->addSpan('1: Italy (85p)');
        $qualP->addBr();
        $qualP->addSpan('1: Netherlands (56p)');
        $qualP->addBr();
        $qualP->addSpan('1: Poland (49p)');
        $qualP->addBr();
        $qualP->addSpan('1: Austria (31p)');
        $qualP->addH2('Finals')->class = 'entry-title';
        $bracketDiv = $div->addDiv('bracketDiv'.$this->id);
        $div->addDiv()->addCss('clear', 'both');
        $div->addScriptFile(config::$baseHref.'/js/contrib/jquery.bracket.min.js');
        $div->addCssFile(config::$baseHref.'/css/contrib/jquery.bracket.min.css');
        $div->addScriptCode('
          var doubleElimination = {
            "teams": [
              ["Spain", "Austria"],
              ["Germany", "Netherlands"],
              ["Finland", "Poland"],
              ["Sweden", "Italy"]
            ],
            results : [[ /* WINNER BRACKET */
              [[1, 0], [1, 0], [1, 0], [0, 1]],
              [[0, 1], [1, 0]],
              [[1, 0]]
            ], [         /* LOSER BRACKET */
              [[1, 0], [1, 0]],
              [[0, 1], [0, 1]],
              [[0, 1]],
              [[1, 0]]
            ], [         /* FINALS */
              [[1, 0]]
            ]]
          }
          $(function() {
            $("#'.$bracketDiv->id.'").bracket({
              init: doubleElimination,
              skipConsolationRound: true
            });
          });
        ');
        return $div;
      } else {
        $p = new paragraph('Results for the '.$this->divisionName.' division are not yet available.');
        return $p;
      }      
    }
        
  }

?>