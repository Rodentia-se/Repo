<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $class = ($class) ? $class : (($_REQUEST['obj']) ? $_REQUEST['obj'] : 'players');
  $class = (isGroup($class, TRUE)) ? $class : ((isObj($class, TRUE)) ? $class::$arrClass : 'players');

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

  switch ($class) {
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
      $title = $class;
    break;
  }

  $page = new page('Registered '.$title);
  
  $page->addH2('Registered '.$title);
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $page->addLi('<a href="#'.$division->shortName.ucfirst($class).'">'.$division->divisionName.'</a>');
      }
    $page->closeUl();
    foreach ($divisions as $division) {
      $objs = $class($division);
      $rows = array();
      $page->startDiv($division->shortName.ucfirst($class));
        if (count($objs) > 0) {
          if ($division->team) {
            if ($division->national) {
              $headers = array('Name', 'Tag', 'Country', 'Members', 'Picture');
            } else {
              $headers = array('Name', 'Tag', 'Members', 'Picture');
            }
          } else {
            $headers = array('Name', 'Tag', 'City', 'Region', 'Country sort', 'Country', 'IFPA Rank', 'IFPA', 'Photo', 'Waiting', 'Paid');
          }
          foreach ($objs as $obj) {
            $rows[] = $obj->getRegRow(TRUE);
          }
          if ($class == 'players') {
            $page->addParagraph('<input type="button" id="'.$division->shortName.'_reloadButton" class="reloadButton" value="Reload the table">'.(($division->type == 'main' && config::$participationLimit[$division->type]) ? ' <span class="right">The maximum number of players is '.config::$participationLimit[$division->type].'.</span>' : ''));
          }
          $page->addTable($division->shortName.'Table', $headers, $rows, 'regTable');
          $page->datatables = TRUE;
          $page->datatablesReload = TRUE;
          $page->addScript('
            var tbl = [];
            tbl["'.$division->shortName.'"] = $("#'.$division->shortName.'Table").dataTable({
              "bProcessing": true,
              "bDestroy": true,
              "bJQueryUI": true,
          	  "sPaginationType": "full_numbers",
              '.(($class == 'players') ? (($division->team) ? '"aoColumnDefs": [
                {"sClass": "icon", "aTargets": [ 4 ] }
              ],' : '"aoColumnDefs": [
                { "aDataSort": [ 6 ], "aTargets": [ 7 ] },
                { "bVisible": false, "aTargets": [ 6 ] },
                { "aDataSort": [ 4 ], "aTargets": [ 5 ] },
                { "bVisible": false, "aTargets": [ 4 ] },
                {"sClass": "icon", "aTargets": [ 5 ] },
                {"sClass": "icon", "aTargets": [ 8 ] }
              ],') : '').'
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
                return true;
              },
              "oLanguage": {
                "sProcessing": "<img src=\"'.config::$baseHref.'/images/ajax-loader-white.gif\" alt=\"Loading data...\">"
              },
              "iDisplayLength": -1,
              "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });
            '.(($class == 'players') ? '$("#'.$division->shortName.'_reloadButton").click(function() {
              tbl["'.$division->shortName.'"].fnReloadAjax("'.config::$baseHref.'/ajax/getPlayers.php?type=registered&obj=division&id='.$division->id.'");
            });' : '').'
          ');
        } else {
          $page->addParagraph('No players have registered for the '.$division->divisionName);
        }
      $page->closeDiv();
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