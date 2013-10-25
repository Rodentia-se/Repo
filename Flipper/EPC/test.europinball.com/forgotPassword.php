<?php

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'/functions/general.php');
  
  printHeader('EPC 2013', __baseHref__);
  printTopper();
  
  $nonce = (isset($_REQUEST['nonce'])) ? $_REQUEST['nonce'] : false;

  function getPlayerFromNonce($dbh, $nonce) {
    $objs = getPlayers($dbh, 'where m.nonce = "'.$nonce.'" and m.tournamentDivision_id = 1');
    if ($objs && count($objs) == 1) {
      $player = $objs[0];
      if (ulNonce::Verify('reset', $nonce)) {
        $player->valid = true;
      } else {
        $player->valid = false;
      }
      return $player;
    } else {
      return false;
    }
  }
  
  function sendResetEmail($dbh, $player) {
    if (validEmail($player->mailAddress)) {
      $nonce = ulNonce::Create('reset');
      $player->setNonce($dbh, $nonce);
      $msg = 'Hello!
        
        You (or someone) has requested your password at Europinball.org to be reset. If you are not aware of this, you can safely ignore this message.
      
        If you want to reset your password, please click on this link or paste the address into your browser.
        
        '.__baseHref__.'/your-pages/password-reset/?nonce='.urlencode($nonce).'
        
        The link will expire in 15 minutes, and can only be used once.
        
        If you encounter any problems, email us at support@europinball.org for assistance.
        
        Regards
        /EPC 2013 organizers
      ';
      mail($player->mailAddress, 'Europinball.org password reset', $msg, 'From: support@europinball.org');
      return true;
    } else {
      return false;
    }
  }
  
  $content = '<h2 class="entry-title">Password reset '.$nonce.'</h2>';

  $player = getCurrentPlayer($dbh, $ulogin);
  if ($player) {
    $content .= 'You are already logged in as '.$player->firstName.' '.$player->lastName.'! If you intended to reset the password for another user, please <a href="'.__baseHref__.'/your-pages/logout/">logout</a> and then go to this page again.';
  } else {
    if ($nonce) {
      $player = getPlayerFromNonce($dbh, $nonce);
      if ($player) {
        if ($player->valid) {
          $resetNonce = ulNonce::Create('resetNonce');
          $content .= '
            <input type="hidden" name="person_id" id="personId" value="'.$player->id.'">
            <input type="hidden" name="resetNonce" id="resetNonce" value="'.$resetNonce.'">
            <p>Your identity as '.$player->firstName.' '.$player->lastName.' has been confirmed. Your username is '.$player->username.'.</p>
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
          $player->setNonce($dbh, null);
          // set password with ajax
          // Redirect to login when successful
        } else {
          $content .= '<p>Your identity code has expired or is invalid.</p>';
          $playerId = $player->id;
        }
      } else {
        $content .= 'We could not not identify you, or you have already used this identity code. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
      }
    } else {
      $username = (isset($_REQUEST['username'])) ? $_REQUEST['username'] : false;
      if ($username) {
        $id = getIdFromUser($dbh, $username);
        if ($id) {
          $player = getPlayerById($dbh, $id);
          if ($player) {
            $playerId = $player->id;
          } else {
            // No player
          }
        } else {
          // No ID
        }
      }
      if (!$playerId) {
        $email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : false;
        if ($email) {
          $player = getPlayerByEmail($dbh, $email);
          if ($player) {
            $playerId = $player->id;
          } else {
            // No player
          }
        } else {
          // No email
        }    
      }
    }
  
    if ($playerId) {
      $player = getPlayerById($dbh, $playerId);
      if ($player) {
        if ($player->mailAddress) {
          if (validEmail($player->mailAddress)) {
            if (sendResetEmail($dbh, $player)) {
              $content .= '<p>We have sent a new email to the registered address for user '.$player->username.' - please click the link in that email.</p>';
            } else {
              $content .= '<p>Something went wrong trying to send you a password reset email. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
            }
          } else {
            $content .= 'There\'s something wrong with the email address registered for you. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
          }
        } else {
          $content .= 'You have no email address registered with us. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
        }
      } else {
        $content .= 'We could not not identify you. Please try again or <a href="mailto:support@europinball.org">email us</a> for assistance.</p>';
      }
    } else if (!$success) {
      $content .= '
        <p>Please specify either username or email address used for your registration:</p>
        <form action="?" method="POST">
          <table>
            <tr>
              <td class="labelTd"><label>Username:</label></td>
              <td><input type="text" name="username" id="username" onchange="resetPass(this);"><span id="usernameSpan" class="errorSpan"></span></td>
            </tr>
            <tr>
              <td class="labelTd"><label>Email address:</label></td>
              <td><input type="test" name="email" id="email" onchange="resetPass(this);"><span id="emailSpan" class="errorSpan"></span></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" value="Submit" id="submit" disabled></td>
            </tr>
          </table>
        </form>
        <script type="text/javascript">document.getElementById(\'username\').focus();</script>
      ';
    }
  }
  
  echo($content);
  printFooter($dbh, $ulogin);
  
?>