<?php

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Admin tools');
  $page->forms = TRUE;
  $page->datatables = TRUE;

  if ($page->reqLogin('You need to be logged in to access this page. If you don\'t have a user, please go to the <a href="'.config::$baseHref.'/registration/">registration page</a>.')) {
    $adminDiv = new div('adminDiv');
    $volunteer = volunteer('login');
    if ($volunteer->receptionist) {
      $tabs = $adminDiv->addTabs(NULL, 'adminTabs');
        $adminTabs = array('players', 'users', 'payments', 'teams', 'groups', 'games', 'scores', 'results', 'volunteers', 'tshirts', 'other');
        foreach ($adminTabs as $adminTab) {
          $tabs->addAjaxTab(config::$baseHref.'/ajax/admin/'.$adminTab.'.php', ucfirst($adminTab));
        }
      //$tabs
      $page->addContent($adminDiv);
    } else {
      $paragraph = new paragraph('You need to be an administrator or receptionist to access this page. Please logout and log back in as administrator.');
      $page->addContent($paragraph);
    }
  }
  $page->submit();

?>