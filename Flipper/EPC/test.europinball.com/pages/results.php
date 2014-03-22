<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $type = ($type) ? $type : (($_REQUEST['obj']) ? $_REQUEST['obj'] : 'players');
  $type = (isGroup($type, TRUE)) ? $type : ((isObj($type, TRUE)) ? $type::$arrClass : 'players');

  if (isId($_REQUEST['tournament_id'])) {
    $tournament = tournament($_REQUEST['tournament_id']);
  }
  if (!$tournament) {
    $tournament = tournament(config::$activeTournament);
  }
  if (!$tournament) {
    $tournament = getTournament();
  }
  if (!$tournament) {
    error('No tournament found!', NULL, FALSE, TRUE);
  }
  $divisions = divisions($tournament);
  if (!$divisions || count($divisions) < 1) {
    error('No divisions found!', NULL, FALSE, TRUE);
  }

  switch ($type) {
    case 'players':
      $title = 'players and teams';
      $divisions->filter('includeInStats');
    break;
    case 'persons':
      $title = 'players';
    break;
    case 'teams':
      $title = 'teams';
      $divisions->filter('includeInStats');
    break;
    case 'games':
    case 'machines':
      $title = 'games';
    break;
    case 'manufacturers':
      $title = 'game manufacturers';
    break;
    default:
      $title = $type;
    break;
  }

  $page = new page('Qualification results ');
  $page->modal = TRUE;
  
  $page->addH2('Results');
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $objs[$division->id] = $type($division);
        $objs[$division->id]->filter('waiting', 0, '>', TRUE);
        if (count($objs[$division->id]) > 0 || config::$showEmptyDivisions) {
          $page->addLi('<a href="#'.$division->shortName.ucfirst($type).'">'.$division->divisionName.'</a>');
        }
      }
      $page->closeUl();
      foreach ($divisions as $division) {
        $rows = array();
        if (count($objs[$division->id]) > 0 || config::$showEmptyDivisions) {
          $page->startDiv($division->shortName.ucfirst($type));
          if ($division->id == 15) {
            $qualGroups = qualGroups($division);
            if ($qualGroups && count($qualGroups) > 0) {
              $div = new div();
              $div->addH2('Qualification group standings')->class = 'entry-title';
              $tabs = $div->addTabs();
              foreach($qualGroups as $qualGroup) {
                $qualDiv = $tabs->addDiv($qualGroup->acronym);
                $standings = $qualGroup->getStandingsImg('standings');
                if ($standings) {
                  $qualDiv->addH2('Standings')->class = 'entry-title';
                  $qualP = $qualDiv->addParagraph();
                  $qualP->addContent($standings);
                }
                $found = TRUE;
                $num = 0;
                while ($found) {
                  $num++;
                  $matches = $qualGroup->getMatchesImg($num);
                  if ($matches) {
                    if ($num == 1) {
                      $qualDiv->addH2('Matches')->class = 'entry-title';
                    }
                    $qualP = $qualDiv->addParagraph();
                    $qualP->addContent($matches);
                  } else {
                    $found = FALSE;
                  }
                }
                if (!$standings && !$matches) {
                  $qualP = $qualDiv->addParagraph('There are no standings available for group '.$qualGroup->acronym.' yet.');
                }
              }
              $page->addContent($div->getHtml());
            }
          } else if ($division->id == 16) {  // TODO: Remove EPC 2014 specifics
            $division->calcPlaces();
            if (count($objs[$division->id]) > 0) {
              if ($type == 'players') {
                $headers = array('Order', 'Place', 'Name', 'Photo', 'Country sort', 'Country', 'Games', 'Points');
              }
              foreach ($objs[$division->id] as $obj) {
                $rows[] = $obj->getResultsRow(TRUE);
              }
              $reloadP = '<input type="button" id="'.$division->shortName.'_reloadButton" class="reloadButton" value="Reload the table">';
              $page->addParagraph($reloadP);
              $page->addTable($division->shortName.'Table', $headers, $rows, 'resultsTable');
              $page->datatables = TRUE;
              $page->datatablesReload = TRUE;
              $page->addScript('
                var tbl = [];
                tbl["'.$division->shortName.'"] = $("#'.$division->shortName.'Table").dataTable({
                  "bProcessing": true,
                  "bDestroy": true,
                  "bJQueryUI": true,
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
                    $("#'.$division->shortName.'Table").css("width", "");
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
                $("#'.$division->shortName.'_reloadButton").click(function() {
                  tbl["'.$division->shortName.'"].fnReloadAjax("'.config::$baseHref.'/ajax/getObj.php?class='.$type.'&type=results&data=division&data_id='.$division->id.'");
                });
              ');
            } else {
              $page->addParagraph('No '.$type.' are registered in the '.strtolower($division->divisionName));
            }
          } else {
            $page->addParagraph('Results for the '.$division->divisionName.' division are not yet available.');
          }
        $page->closeDiv();
      }
    }
  $page->closeDiv();
  $page->addScript('
    var index = "key";
    var dataStore = window.sessionStorage;
    try {
      var oldIndex = dataStore.getItem(index);
    } catch(e) {
      var oldIndex = 0;
    }
    $("#tabs").tabs({
      active: oldIndex,
      activate: function(event, ui) {
        var newIndex = ui.newTab.parent().children().index(ui.newTab);
        dataStore.setItem(index, newIndex) 
      }
    });
  ');
  
  $page->submit();

?>