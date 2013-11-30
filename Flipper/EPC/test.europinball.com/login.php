<?php

$page = new page('Login', true);

if ($_REQUEST['action']) {
  $page->login->action();
}

$login = $page->reqLogin('Please provide your credentials', true);

if ($login) {
  $page->content = 'You are already logged in!';
}

$page->submit(FALSE, TRUE);

?>