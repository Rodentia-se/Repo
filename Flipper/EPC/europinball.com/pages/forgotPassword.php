<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  $reqNonce = (isset($_REQUEST['resetNonce'])) ? $_REQUEST['resetNonce'] : false;

  function getPersonFromNonce($regNonce) {
    $person = person(array('nonce' => $reqNonce), TRUE);
    if ($person) {
      if (ulNonce::Verify('reqNonce', $reqNonce)) {
        $person->valid = true;
      } else {
        $person->valid = false;
      }
      return $person;
    } else {
      return false;
    }
  }
  
  
  $content = '<h2 class="entry-title">Password reset</h2>';

  if ($page->loggedin()) {
    $person = person('login');
    $page->addParagraph('You are already logged in as '.$person->name.'. You can go to the <a href="'.config::$baseHref.'/edit" class="buttonLink">Profile editor</a> to change your login credentials.');
    $page->addParagraph('If you are not '.$person->name.' and intended to reset the password for someone else, you need to '.page::getButton('log out').' first.');
    $page->addForm('log out', array('action' => 'logout'));
  } else {
    if ($nonce) {
      $person = getPersonFromNonce($reqNonce);
      if ($person) {
        if ($person->valid) {
          $resetNonce = ulNonce::Create('resetNonce');
          $content .= '
            <input type="hidden" name="person_id" id="personId" value="'.$person->id.'">
            <input type="hidden" name="resetNonce" id="resetNonce" value="'.$resetNonce.'">
            <p>Your identity as '.$person->firstName.' '.$person->lastName.' has been confirmed. Your username is '.$person->username.'.</p>
            <table>
              <tr>
                <td class="labelTd"><label>Please set a new password:</label><td><input name="password" id="password" type="password" onkeyup="checkResetPassword(this);"><span id="passwordSpan" class="errorSpan"></span></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="submit" value="Submit" id="submit" onclick="resetPassword(this);" disabled></td>
              </tr>
            </table>
          ';
          $success = true;
          $person->setNonce(null);
          // set password with ajax
          // Redirect to login when successful
        } else {
          $content .= '<p>Your identity code has expired or is invalid.</p>';
          $playerId = $person->id;
        }
      } else {
        $content .= 'We could not not identify you, or you have already used this identity code. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
      }
    } else {
      $username = (isset($_REQUEST['username'])) ? $_REQUEST['username'] : false;
      if ($username) {
        $person = person(array('username' => $username), TRUE);
      }
      if (!$person) {
        $email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : false;
        if ($email) {
        $person = person(array('mailAddress' => $email), TRUE);
        }    
      }
    }
  
    debug($person);
      if ($person && isId($person->id)) {
        if ($person->mailAddress) {
          if (person::validateMailAddress($player->mailAddress)) {
            if (config::$login->sendResetEmail($person)) {
              $content .= '<p>We have sent a new email to the registered address for user '.$person->username.' - please click the link in that email.</p>';
            } else {
              $content .= '<p>Something went wrong trying to send you a password reset email. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
            }
          } else {
            $content .= 'There\'s something wrong with the email address registered for you. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
          }
        } else {
          $content .= 'You have no email address registered with us. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
        }
    } else if (!$success) {
      $content .= '
        <p>Please specify either username or email address used for your registration:</p>
        <form action="?" method="POST">
          <table>
            <tr>
              <td class="labelTd"><label>Username:</label></td>
              <td><input type="text" name="username" id="username"><span id="usernameSpan" class="errorSpan"></span></td>
            </tr>
            <tr>
              <td class="labelTd"><label>Email address:</label></td>
              <td><input type="test" name="email" id="email""><span id="emailSpan" class="errorSpan"></span></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" value="Submit" id="submit"></td>
            </tr>
          </table>
        </form>
        <script type="text/javascript">document.getElementById(\'username\').focus();</script>
      ';
    }
  }
  
  $page->addContent($content);
  $page->submit();
?>