<?php

  class machines extends group {
    
    public static $objClass = 'machine';
    
    public function __construct($data = NULL, $prop = NULL, $cond = 'and') {
      parent::__construct($data, $prop, $cond);
    }

    public function getTable($id = NULL, $class = NULL, array $headers = NULL) {
      $divisionIds = array();
      foreach ($this as $obj) {
        if (!in_array($obj->tournamentDivision_id, $divisionIds)) {
          $divisionIds[] = $obj->tournamentDivision_id;
        }
        $tbody[$obj->tournamentDivision_id][] = $obj->getTr();
      }
      sort($divisionIds);
      if (count($divisionIds) > 1) {
        $tabs = new tabs(NULL, 'divisionTabs');
      } else {
        $tabs = new div('machineDiv');
      }
      foreach($divisionIds as $divisionId) {
        $division = division($divisionId);
        $headers = array('Name', 'Acronym', 'Manufacturer', 'Owner', 'IPDB', 'Rules', 'Year');
        $tableProps = array(
          'aoColumnDefs' => '[
            {"sClass": "icon", "aTargets": [ 5 ] }
          ]',
          'fnDrawCallback' = '
            function() {
              $(this).css("width", "");
              $(".photoPopup").each(function() {
                $(this).dialog({
                  autoOpen: false,
                  modal: true, 
                  width: "auto",
                  height: "auto"
                });
              });
              $(".photoIcon").click(function() {
                var photoDiv = $(this).data("photodiv");
                $("#" + photoDiv).dialog("open");
                $(document).on("click", ".ui-widget-overlay", function() {
                  $("#" + photoDiv).dialog("close");
                });
              });
              return true;
            },
          '
        );
        $thead = new tr();
        foreach ($headers as $label) {
          $thead->addTh($label);
        }
        $div = $tabs->addDiv($divisionId.'_divisionDiv', NULL, array('data-title' => ucfirst($division->divisionName)));
        $table = $div->addDatatables($tbody[$divisionId], $thead, $divisionId.'_divisionTable', NULL, NULL, $tableProps);
      }
      return $tabs;
    }
    
  }

?>