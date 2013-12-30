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

  $page = new page('Registered '.$title);
  
  $page->addH2('Registered '.$title);
  $page->startDiv('tabs');
    $page->startUl();
      foreach ($divisions as $division) {
        $objs[$division->id] = $type($division);
        if (count($objs[$division->id]) > 0) {
          $page->addLi('<a href="#'.$division->shortName.ucfirst($type).'">'.$division->divisionName.'</a>');
        }
      }
      $page->closeUl();
      foreach ($divisions as $division) {
        $rows = array();
        if (count($objs[$division->id]) > 0) {
          $page->startDiv($division->shortName.ucfirst($type));
          if ($type == 'players') {
            if ($division->team) {
              if ($division->national) {
                $headers = array('Name', 'Tag', 'Country', 'Members', 'Picture');
                $aoColumnDefs = '{"sClass": "icon", "aTargets": [ 4 ] }';
              } else {
                $headers = array('Name', 'Tag', 'Members', 'Picture');
                $aoColumnDefs = '{"sClass": "icon", "aTargets": [ 3 ] }';
              }
            } else {
              $headers = array('Name', 'Tag', 'City', 'Region', 'Country sort', 'Country', 'IFPA Rank', 'IFPA', 'Photo', 'Waiting', 'Paid');
              $aoColumnDefs = '
                { "aDataSort": [ 6 ], "aTargets": [ 7 ] },
                { "bVisible": false, "aTargets": [ 6 ] },
                { "aDataSort": [ 4 ], "aTargets": [ 5 ] },
                { "bVisible": false, "aTargets": [ 4 ] },
                {"sClass": "icon", "aTargets": [ 5 ] },
                {"sClass": "icon", "aTargets": [ 8 ] }
              ';
            }
          } else if ($type == 'machines') {
            $headers = array('Name', 'Acronym', 'Manufacturer', 'Owner', 'IPDB', 'Rules', 'Year');
            $aoColumnDefs = '{"sClass": "icon", "aTargets": [ 5 ] }';
          }
          foreach ($objs as $obj) {
            $rows[] = $obj->getRegRow(TRUE);
          }
          if ($type == 'players') {
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
              '.(($aoColumnDefs) ? '"aoColumnDefs": [
                '.$aoColumnDefs.'
              ],' : '').'
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
            '.(($type == 'players') ? '$("#'.$division->shortName.'_reloadButton").click(function() {
              tbl["'.$division->shortName.'"].fnReloadAjax("'.config::$baseHref.'/ajax/getPlayers.php?type=registered&obj=division&id='.$division->id.'");
            });' : '').'
          ');
        } else {
          $page->addParagraph('No '.$type.' are registered in the '.strtolower($division->divisionName));
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