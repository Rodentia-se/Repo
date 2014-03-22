<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $class = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : NULL;
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : NULL;
  
  switch ($class) {
    case 'division':
      if (isId($id)) {
        $division = division($id);
        if (isDivision($division)) {
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
              echo($div->getHtml());
            }
          } else if ($division->id == 16) {  // TODO: Remove EPC 2014 specifics
            $division->calcPlaces();
            $div = new div();
              $headers = array('Order', 'Place', 'Name', 'Photo', 'Country sort', 'Country', 'Games', 'Points');
              $reloadP = $div->addParagraph($reloadP);
              $reloadButton = $reloadP->addButton('Reload the table', $division->shortName.'_reloadButton', array('class' => 'reloadButton'));
              $table = $div->addTable(NULL, $headers, $division->shortName.'Table', 'resultsTable');
              $reloadButton->addClick('
                $("#'.$table->id.'").dataTable().fnReloadAjax("'.config::$baseHref.'/ajax/getObj.php?class=players&type=results&data=division&data_id='.$division->id.'");
              ');
              $div->addScriptCode('
                $(document).ready(function() {
                  $("#'.$table->id.'").dataTable({
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
                  $("#'.$table->id.'").dataTable().fnReloadAjax("'.config::$baseHref.'/ajax/getObj.php?class=players&type=results&data=division&data_id='.$division->id.'");
                });
              ');
            //Div
            echo($div->getHtml());
          } else {
            $p = new paragraph('Results for the '.$division->divisionName.' division are not yet available.');
            echo($p->getHtml());
          }
        } else {
          $p = new paragraph('Invalid division');
          echo($p->getHtml());
        }
      } else {
        $p = new paragraph('Invalid division ID');
        echo($p->getHtml());
      }
    break;
    default:
      $p = new paragraph('Invalid class');
      echo($p->getHtml());
    break;
  }

?>          
