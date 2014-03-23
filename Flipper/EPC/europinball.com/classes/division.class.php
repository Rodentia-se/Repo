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
        $div = new div();
        $div->addH2('Qualification group standings')->class = 'entry-title';
        $levelsDiv = $div->addDiv('qualGroupLevelsDiv'.$this->id);
        $qualGroups = qualGroups($this);
        $level = 1;
        while ($level) {
          $levelGroups = $qualGroups->getFiltered('level', $level);
          if ($levelGroups && count($levelGroups) > 0) {
            $levelsDiv->addH3('Round '.$level);
            $tabs = $levelsDiv->addTabs();
            foreach($levelGroups as $qualGroup) {
              $qualDiv = $tabs->addDiv($qualGroup->acronym);
              $qualDiv->addContent($qualGroup->getStandings());
            }
            $level++;
          } else {
            if ($level == 1) {
              $div->addParagrapg('No qualification group standings are available');
            }
            unset($level);
          }
        }
        $div->addScriptCode('
          $(document).ready(function() {
            $("#'.$levelsDiv->id.'").accordion({
              collapsible: true,
              heightStyle: "content"
            });
          });
        ');
        return $div;
      } else if ($this->id == 16) {  // TODO: Remove EPC 2014 specifics
        if (!file_exists(config::$baseDir.'/logs/calcPlaces_div'.$this->id.'.lock')) {
          $fh = fopen(config::$baseDir.'/logs/calcPlaces_div'.$this->id.'.lock', 'w');
          fwrite($fh, 'Place calculation has been started');
          fclose($fh);
          $this->calcPlaces();
        }
        $div = new div();
          $div->addH2('Finals')->class = 'entry-title';
          $semiDiv = $div->addDiv();
            $machine[1] = machine(402);
            $player1[1] = player(2088);
              $player1[1]->score = '1.299.510';
            $player2[1] = player(2292);
              $player2[1]->score = '1.112.380';
            $player3[1] = player(2482);
              $player3[1]->score = '712.080';
            $player4[1] = player(1847);
              $player4[1]->score = '657.040';
            $machine[2] = machine(405);
            $player1[2] = player(2150);
              $player1[2]->score = '2.340.550';
            $player2[2] = player(2256);
              $player2[2]->score = '2.140.490';
            $player3[2] = player(2062);
              $player3[2]->score = '438.290';
            $player4[2] = player(2115);
              $player4[2]->score = '91.070';
            for ($semi = 1; $semi <= 2; $semi++) {
              $groupDiv = $semiDiv->addDiv();
                $groupDiv->addCss('float', 'left');
                $groupDiv->addCss('margin-right', '40px');
                $groupDiv->addCss('margin-bottom', '20px');
                $h2 = $groupDiv->addH2('Semifinal on '.$machine[$semi]->getLink());
                  $h2->escape = FALSE;
                  $h2->class = 'bold';
                  $h2->addCss('clear', 'none');
                $playersDiv = $groupDiv->addDiv();
                  $playersDiv->addCss('float', 'left');
                $flagDiv = $groupDiv->addDiv();
                  $flagDiv->addCss('float', 'left');
                $scoresDiv = $groupDiv->addDiv();
                  $scoresDiv->addCss('float', 'left');
                for ($place = 1; $place <= 4; $place++) {
                  $playerDiv = $playersDiv->addDiv();
                    $playerDiv->addCss('height', '18px');
                    $playerDiv->addCss('margin-right', '20px');
                    $playerDiv->addSpan($place.': '.${'player'.$place}[$semi]->getLink())->escape = FALSE;
                    $playerDiv->addBr();
                  $flagDiv->addSpan(((is_object(${'player'.$place}[$semi]->country)) ? ${'player'.$place}[$semi]->country->getIcon() : ''))->escape = FALSE;
                  $flagDiv->addBr();
                  $scoreDiv = $scoresDiv->addDiv();
                    $scoreDiv->addCss('height', '18px');
                    $scoreDiv->addCss('margin-left', '20px');
                    $scoreDiv->addSpan(${'player'.$place}[$semi]->score)->escape = FALSE;
                    $scoreDiv->addBr();
                  //scoreDiv
                }
              //groupDiv
            }
          $finalDiv = $div->addDiv();
            $machine = machine(490);
            $h2 = $finalDiv->addH2('Final on '.$machine->getLink());
              $h2->class = 'bold';
              $h2->escape = FALSE;
            $player1 = player(2088);
              $player1->score = '215.170';
            $player2 = player(2150);
              $player2->score = '174.150';
            $player3 = player(2256);
              $player3->score = '109.360';
            $player4 = player(2292);
              $player4->score = '74.160';
            $playersDiv = $groupDiv->addDiv();
              $playersDiv->addCss('float', 'left');
            $flagDiv = $groupDiv->addDiv();
              $flagDiv->addCss('float', 'left');
            $scoresDiv = $groupDiv->addDiv();
              $scoresDiv->addCss('float', 'left');
            for ($place = 1; $place <= 4; $place++) {
              $playerDiv = $playersDiv->addDiv();
                $playerDiv->addCss('height', '18px');
                $playerDiv->addCss('margin-right', '20px');
                $playerDiv->addSpan($place.': '.${'player'.$place}->getLink())->escape = FALSE;
                $playerDiv->addBr();
              $flagDiv->addSpan(((is_object(${'player'.$place}->country)) ? ${'player'.$place}->country->getIcon() : ''))->escape = FALSE;
              $flagDiv->addBr();
              $scoreDiv = $scoresDiv->addDiv();
                $scoreDiv->addCss('height', '18px');
                $scoreDiv->addCss('margin-left', '20px');
                $scoreDiv->addSpan(${'player'.$place}->score)->escape = FALSE;
                $scoreDiv->addBr();
              //scoreDiv
            }
          $h2 = $div->addH2('Qualifications');
          $h2->class = 'entry-title';
          $h2->addCss('margin-top', '40px');
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
        $h2 = $group1div->addH2('Group 1');
        $h2->class = 'entry-title';
        $h2->addCss('clear', 'none');        
        $group1div->addSpan('1: Spain (63p)');
        $group1div->addBr();
        $group1div->addSpan('2: Finland (43p)');
        $group1div->addBr();
        $group1div->addSpan('3: Germany (41p)');
        $group1div->addBr();
        $group1div->addSpan('4: Sweden (38p)');
        $group1div->addBr();
        $group1div->addSpan('5: Switzerland (36p)');
        $group1div->addBr();
        $group1div->addSpan('6: Belgium (35p)');
        $group1div->addBr();
        $group1div->addSpan('7: Romania (21p)');
        $group1div->addBr();
        $group1div->addSpan('8: Hungary (18p)');
        $group2div = $qualDiv->addDiv();
        $h2 = $group2div->addH2('Group 2');
        $h2->class = 'entry-title';
        $h2->addCss('clear', 'none');        
        $group2div->addSpan('1: Italy (85p)');
        $group2div->addBr();
        $group2div->addSpan('2: Netherlands (56p)');
        $group2div->addBr();
        $group2div->addSpan('3: Poland (49p)');
        $group2div->addBr();
        $group2div->addSpan('4: Austria (31p)');
        $group2div->addBr();
        $group2div->addSpan('5: France (28p)');
        $group2div->addBr();
        $group2div->addSpan('6: UK (25p)');
        $group2div->addBr();
        $group2div->addSpan('7: Denmark (21p)');
        $qualDiv->addCss('margin-bottom', '40px');
        $div->addH2('Finals')->class = 'entry-title';
        $bracketDiv = $div->addDiv('bracketDiv'.$this->id);
        $div->addDiv()->class = 'clearer';
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