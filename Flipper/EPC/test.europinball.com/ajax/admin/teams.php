<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $tournament = tournament('active');
      $prefix = 'teams';
      ${$prefix.'Div'} = new div($prefix.'Div');
        ${$prefix.'Div'}->data_title = ucfirst($prefix);
        ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
        ${$prefix.'DivisionTabs'} = ${$prefix.'Div'}->addTabs(NULL, ${$prefix.'Div'}->id.'Tabs');
          foreach (config::$activeTeamDivisions as $divisionType) {
            $division = division($tournament, $divisionType);
            $subPrefix = $divisionType;
            $teams = teams($division); 
            ${$prefix.$subPrefix.'Div'} = ${$prefix.'DivisionTabs'}->addDiv($prefix.$subPrefix.'Div');
              ${$prefix.$subPrefix.'Div'}->dataTitle = $division->divisionName;
              ${$prefix.$subPrefix.'SelectDiv'} = ${$prefix.$subPrefix.'Div'}->addDiv();
                ${$prefix.$subPrefix.'Select'} = ${$prefix.$subPrefix.'SelectDiv'}->addContent($teams->getSelectObj($prefix.$subPrefix.'teams', NULL, 'Teams'));
                ${$prefix.$subPrefix.'Select'}->addCombobox();
                ${$prefix.$subPrefix.'Select'}->addValueSpan('Team ID:');
                ${$prefix.'editSections'} = ($division->national) ? array('edit', 'members', 'admin') : array('edit', 'photo', 'members', 'admin');
                foreach (${$prefix.'editSections'} as $editSection) {
                  ${$prefix.'editScripts'} .= '
                    $.post("'.config::$baseHref.'/ajax/getObj.php", {class: "team", type: "'.$editSection.'", id: $(this).val()})
                    .done(function(data) {
                      $("#" + el.id + "'.ucfirst($editSection).'Div").html(data);
                      modals--;
                      if (modals == 0) {
                        $("body").removeClass("modal");
                        $("#" + el.id + "Tabs").show();
                      }
                    });
                  ';
                }
                ${$prefix.$subPrefix.'Select'}->addChange('
                  var el = this;
                  $("#" + el.id + "Tabs").hide();
                  if ($(el).val() != 0) {
                    $("body").addClass("modal");
                    var modals = '.count(${$prefix.'editSections'}).';
                    '.${$prefix.'editScripts'}.'
                  }
                ');
                ${$prefix.$subPrefix.'Div'}->addFocus('#'.${$prefix.$subPrefix.'Select'}->id.'_combobox', TRUE);
              //$teamsSelectDiv 
              $teamEditTabs = ${$prefix.$subPrefix.'Div'}->addTabs(NULL, ${$prefix.$subPrefix.'Select'}->id.'Tabs', 'hidden');
                $teamEditDiv = $teamEditTabs->addDiv(${$prefix.$subPrefix.'Select'}->id.'EditDiv', NULL, array('data-title' => 'Team profile'));
                //$teamEditDiv
                $teamPhotoEditDiv = ($division->national) ? NULL : $teamEditTabs->addDiv(${$prefix.$subPrefix.'Select'}->id.'PhotoDiv', NULL, array('data-title' => 'Team photo/logo'));
                //$teamPhotoEditDiv
                $teamMembersEditDiv = $teamEditTabs->addDiv(${$prefix.$subPrefix.'Select'}->id.'MembersDiv', NULL, array('data-title' => 'Team members'));
                //$teamMembersEditDiv
                $teamAdminEditDiv = $teamEditTabs->addDiv(${$prefix.$subPrefix.'Select'}->id.'AdminDiv', NULL, array('data-title' => 'Admin settings'));
                //$teamAdminEditDiv
                $teamEditTabs->addCss('margin-top', '15px');
                //$teamsEditDiv
              //$teamEditTabs
            //${$prefix.$subPrefix.'Div'}
          }
        //${$prefix.'DivisionTabs'}
        ${$prefix.'Div'}->addParagraph('More coming soon...')->addCss('margin-top', '15px');
      //${$prefix.'Div'}
      echo ${$prefix.'Div'}->getHtml();
    } else {
      echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
    }

  ?>