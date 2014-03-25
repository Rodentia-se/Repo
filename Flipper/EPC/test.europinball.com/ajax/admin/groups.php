<?php

  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/functions/init.php');

  $volunteer = volunteer('login');
  if ($volunteer->receptionist) {
    $division = division('main');
    $persons = persons($division);
    $prefix = 'groups';
    ${$prefix.'Div'} = new div($prefix.'Div');
      ${$prefix.'Div'}->data_title = ucfirst($prefix);
      ${$prefix.'Div'}->addH2(${$prefix.'Div'}->data_title, array('class' => 'entry-title'));
      $tabs = ${$prefix.'Div'}->addTabs(NULL, 'groupTabs');
        $qualGroups = qualGroups($division);
        $qualGroups->order('acronym');
        foreach ($qualGroups as $qualGroup) {
          if ($qualGroup->level == 1) {
            $tabs->addAjaxTab(config::$baseHref.'/ajax/getObj.php?type=edit&class=qualGroup&id='.$qualGroup->id, ucfirst($qualGroup->acronym));
          }
        }
      //$tabs
    //${$prefix.'Div'}
    echo ${$prefix.'Div'}->getHtml();
  } else {
    echo 'Admin login required. Please make sure you are logged in as an administrator and try again.';
  }

?>