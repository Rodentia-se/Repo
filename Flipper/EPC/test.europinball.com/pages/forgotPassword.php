<?php

  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/functions/init.php');

  $page = new page('Register');
  
  $reqNonce = (isset($_REQUEST['reqNonce'])) ? $_REQUEST['reqNonce'] : false;
  function getPersonFromNonce($reqNonce) {
    if ($reqNonce) {
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
    } else {
      return FALSE;
    }
  }
  
  
  $page->addH2('Password reset');

  if ($page->loggedin()) {
    $person = person('login');
    if ($_REQUEST['action'] == 'reset') {
      $page->addParagraph('You are now logged in as '.$person->name.', and yuour password was changed. If you want to, you can go to the <a href="'.config::$baseHref.'/edit" class="buttonLink">Profile editor</a> to also change your username.');
    } else {
      $page->addParagraph('You are already logged in as '.$person->name.'. You can go to the <a href="'.config::$baseHref.'/edit" class="buttonLink">Profile editor</a> to change your login credentials.');
      $page->addParagraph('If you are not '.$person->name.' and intended to reset the password for someone else, you need to '.page::getButton('log out').' first.');
      $page->addForm('log out', array('action' => 'logout'));
    }
  } else {
    if ($reqNonce) {
      $person = getPersonFromNonce($reqNonce);
      if ($person) {
        if ($person->valid) {
          $page->addParagraph('You have been identified as '.$person->name.(($person->shortName) ? ' ('.$person->shortName.')' : '').(($person->cityName || $person->countryName) ? ' from '.(($person->cityName) ? $person->cityName.', ' : '').$person->countryName : '').'.');
          $page->addParagraph('If this is not corret, please '.page::getButton('reload').' this page and try again.');
          $page->addForm('reload');
          $resetNonce = ulNonce::Create('resetNonce');
          $page->addNewUser('Provide new credentials', $person->id, 'reset');
          $page->addScript('
            $("#resetaction").val("reset");
            $("#resetnewUserButton").val("Submit");
            $("#resetnonce").val("'.$resetNonce.'");
          ');
          $success = true;
          $person->setNonce(null);
        } else {
          $page->addParagraph('Your identity code has expired or is invalid.');
        }
      } else {
        $page->addParagraph('We could not not identify you, or you have already used this identity code. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.');
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
      if ($person && isId($person->id)) {
        if ($person->mailAddress) {
          if (person::validateMailAddress($person->mailAddress)) {
            if (config::$login->sendResetEmail($person)) {
              $page->addParagraph('We have sent a new email to the registered address for user '.$person->username.': '.$person->mailAddress.' - please click the link in that email.');
              $page->addParagraph('If you are using Google\'s GMail, make sure to check your spam folder.');
              $page->addParagraph('If you use this function multiple times, only the link in the last email will work. The earlier ones will be rendered invalid.');
            } else {
              $page->addParagraph('Something went wrong trying to send you a password reset email. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.');
            }
          } else {
            $page->addParagraph('There\'s something wrong with the email address registered for you. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.');
          }
        } else {
          $page->addParagraph('You have no email address registered with us. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.');
        }
      } else {
        $page->addContent('
          <p>Please specify either username or email address used for your registration:</p>
          <form action="?" method="POST">
            <table>
              <tr>
                <td class="labelTd"><label>Username:</label></td>
                <td><input type="text" name="username" id="username"><span id="usernameSpan" class="errorSpan"></span></td>
              </tr>
              <tr>
                <td class="labelTd"><label>Email address:</label></td>
                <td><input type="text" name="email" id="email""><span id="emailSpan" class="errorSpan"></span></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="submit" value="Submit" id="submit"></td>
              </tr>
            </table>
          </form>
          <script type="text/javascript">document.getElementById(\'username\').focus();</script>
        ');
      }
    }
  }
    
  $page->submit();
?>