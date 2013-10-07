<?php
  require_once('../contrib/recaptchalib.php');
  header('Content-Type: text/plain');

  $privatekey = '6Lc3d-gSAAAAACY7nHWMxyLNBe3SWmienod_F0vR';
  $resp = recaptcha_check_answer ($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['chall'], $_POST['resp']);
  
  if (!$resp->is_valid) {
    echo 'Invalid: '.$resp->error.' IP: '.$_SERVER['REMOTE_ADDR'].' Chall: '.$_POST['chall'].' Resp: '.$_POST['resp'];
  } else {
    echo 'Valid';
  }
  
?>
