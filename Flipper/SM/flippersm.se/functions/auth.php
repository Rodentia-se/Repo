<?php

// Start a secure session if none is running
if (!sses_running()) {
	sses_start();
}

function changeUser($dbh, $ulogin, $newUser, $newPass) {
  if (isAppLoggedIn()) {
    // Delete old account
    $player = getCurrentPlayer($dbh, $ulogin);
    $player->username = $newUser;
    $player->password = $newPass;
    if (isset($_REQUEST['changeNonce']) && ulNonce::Verify('change', $_REQUEST['changeNonce'])) {
      if ($ulogin->Username($_SESSION['uid']) == $newUser) {
        if ($ulogin->SetPassword($_SESSION['uid'], $newPass)) {
          echo('Lösenordet för '.$newUser.' byttes<br />');
          return true;
        } else {
          echo('Lösenordsbyte för '.$newUser.' misslyckades<br />');
          return false;
        }
      } else {
        if (updateUser($dbh, $player, $ulogin)) {
          if ($ulogin->DeleteUser($_SESSION['uid'])) {
            appLogout($ulogin);
            $ulogin->Authenticate($newUser,  $newPass);
            if ($ulogin->IsAuthSuccess()) {
              echo('Inloggad som '.$newUser.'<br />');
              return true;
            } else {
              echo('Kunde inte logga in som '.$newUser.'<br />');
              return false;
            }
          } else {
            echo('Kunde inte ta bort det gamla kontot<br />');
            return false;
          }
        } else {
          echo('Kunde inte skapa det nya kontot<br />');
          return false;
        }
      }
    } else {
      echo('Felaktig nonce: '.$_POST['changeNonce'].'<br />');
      return false;
    }
  } else {
    echo('Du måste logga in först<br />');
    return false;
  }
}

// We define some functions to log in and log out,
// as well as to determine if the user is logged in.
// This is needed because uLogin does not handle access control
// itself.
function isAppLoggedIn() {
	return isset($_SESSION['uid']) && isset($_SESSION['username']) && isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn']===true);
}

function appLogin($uid, $username, $ulogin) {
	$_SESSION['uid'] = $uid;
	$_SESSION['username'] = $username;
	$_SESSION['loggedIn'] = true;

	if (isset($_SESSION['appRememberMeRequested']) && ($_SESSION['appRememberMeRequested'] === true)) {
		// Enable remember-me
		if ( !$ulogin->SetAutologin($username, true)) {
			echo "Kan inte slå på autologin<br>";
    }
		unset($_SESSION['appRememberMeRequested']);
	}	else {
		// Disable remember-me
		if ( !$ulogin->SetAutologin($username, false)) {
			echo 'Kan inte stänga av autologin<br>';
    }
	}
}

function appLoginFail($uid = null, $username = null, $ulogin = null) {
	// Note, in case of a failed login, $uid, $username or both
	// might not be set (might be NULL).
	$response = json_decode(getError('Login misslyckades för '.$username, false))->reason;
  echo $response->reason;
  echo $response;
}

function appLogout($ulogin = null) {
  if ($ulogin) {  
    $ulogin->SetAutologin($_SESSION['username'], false);
  }
	unset($_SESSION['uid']);
	unset($_SESSION['username']);
	unset($_SESSION['loggedIn']);
}

function checkLogin($dbh, $ulogin, $req = true, $title = 'Du måste logga in för att kunna titta på den här sidan') {
  $action = @$_REQUEST['action'];
  $msg = null;
  if (isAppLoggedIn()) {
    if ($action == 'logout') { // We've been requested to log out
      appLogout($ulogin);
    } else if ($action == 'changeUsername') {
      echo('Ändrar loginuppgifter...<br />');
      $ulogin->Authenticate($_REQUEST['currentUsername'], $_REQUEST['currentPassword']);
      if ($ulogin->IsAuthSuccess()) {
        changeUser($dbh, $ulogin, $_REQUEST['username'], $_REQUEST['newPassword']);
      } else {
        echo('Fel loginuppgifter för nuvarande login<br />');
      }
    }
  } else if (!isAppLoggedIn()) {
    // We've been requested to log in
    if ($action == 'login') {
      // Here we verify the nonce, so that only users can try to log in
      // to whom we've actually shown a login page. The first parameter
      // of Nonce::Verify needs to correspond to the parameter that we
      // used to create the nonce, but otherwise it can be anything
      // as long as they match.
      if (isset($_POST['nonce']) && ulNonce::Verify('login', $_POST['nonce'])) {
        // We store it in the session if the user wants to be remembered. This is because
        // some auth backends redirect the user and we will need it after the user
        // arrives back.
        if (isset($_POST['autologin'])) {
          $_SESSION['appRememberMeRequested'] = true;
        } else {
          unset($_SESSION['appRememberMeRequested']);
        }
        // This is the line where we actually try to authenticate against some kind
        // of user database. Note that depending on the auth backend, this function might
        // redirect the user to a different page, in which case it does not return.
        $ulogin->Authenticate($_POST['username'],  $_POST['password']);
        if ($ulogin->IsAuthSuccess()) {
          // Since we have specified callback functions to uLogin,
          // we don't have to do anything here.
        }
      } else {
        $msg = 'Felaktig nonce: '.$_POST['nonce'];
      }
    } else if ($action == 'autologin'){	// We were requested to use the remember-me function for logging in.
      // Note, there is no username or password for autologin ('remember me')
      if (!$ulogin->IsAuthSuccess()) {
        $msg = 'Autologin misslyckades';
      } else {
        $msg = 'Autologin ok';
      }
    }
  }
  if (!isAppLoggedIn()) {
    if ($req == true) {
      echo(showLogin($ulogin, $title));
    }
    echo($msg);
    return false;
  } else {
    return true;
  }
}

function showLogin($ulogin, $title = 'Du måste logga in för att komma åt den här sidan') {
  $content = '
  	<h2 class="loginTable">'.$title.'</h2>
    <script type="text/javascript">
      (function($) {
        $.fn.listenForChange = function(options) {
          settings = $.extend({
            interval: 200 // in microseconds
          }, options);

          var jquery_object = this;
          var current_focus = null;

          jquery_object.filter(":input").add(":input", jquery_object).focus( function() {
            current_focus = this;
          }).blur( function() {
            current_focus = null;
          });

          setInterval(function() {
            // allow
            jquery_object.filter(":input").add(":input", jquery_object).each(function() {
              // set data cache on element to input value if not yet set
              if ($(this).data(\'change_listener\') == undefined) {
                $(this).data(\'change_listener\', $(this).val());
                return;
              }
              // return if the value matches the cache
              if ($(this).data(\'change_listener\') == $(this).val()) {
                return;
              }
              // ignore if element is in focus (since change event will fire on blur)
              if (this == current_focus) {
                return;
              }
              // if we make it here, manually fire the change event and set the new value
              $(this).trigger(\'change\');
              $(this).data(\'change_listener\', $(this).val());
            });
          }, settings.interval);
          return this;
        };
      })(jQuery);
      $(document).ready(function() {
        setTimeout(function() {
          if (document.getElementById(\'usernameLogin\').value != \'\') {
            document.getElementById(\'loginButton\').disable = false;
          }
        }, 500);
        $("#loginForm").listenForChange();
      }); 
    </script>
    <div id="loginDiv" class="loginDiv">
     	<form action="'.$_SERVER['REQUEST_URI'].'" method="POST" id="loginForm">
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="loggedIn" id="loggedIn" value="false">
        '.(($_REQUEST['obj']) ? '<input type="hidden" name="obj" id="obj" value="'.$_REQUEST['obj'].'">' : '').'
        '.(($_REQUEST['id']) ? '<input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'">' : '').'
        <input type="hidden" name="baseHref" id="baseHref" value="'.__baseHref__.'">
			  <input type="hidden" id="nonce" name="nonce" value="'.ulNonce::Create('login').'">
        <div id="usernameDiv">
          <label for="username">Användarnamn:</label>
  		    <input type="text" name="username" id="usernameLogin" class="mandatory" onkeyup="login(this);" oninput="login(this);">
          <span id="usernameLoginSpan" class="errorSpan">*</span>
        </div>
        <div id="passwordDiv">
          <label for="password">Lösenord:</label>
          <input type="password" name="password" id="passwordText" class="mandatory" onkeyup="login(this);" oninput="login(this);">
          <span id="passwordSpan" class="errorSpan">*</span>
        </div>
        <div id="autologinDiv">
          <label for="autologin" class="infoLabel">Kom ihåg mig:</label>
          <input type="checkbox" name="autologin" value="1" id="autologinCheckbox">
        </div>
        <div id="loginDiv">
    		  <input type="submit" value="Logga in" id="loginButton" onclick="login(this);" disabled>&nbsp;&nbsp;
          <a href="'.__baseHref__.'/?s=losenreset" class="italic">Glömt lösenordet?</a>
        </div>
  	  </form>
    </div>
  ';
  return $content;
}

function showChangeUsername($dbh, $username) {
  $username = $_SESSION['username'];
  $id = getIdFromUser($dbh, $username);
  $content = '
    <div id="changeUserDiv">
      <form action="'.$_SERVER['REQUEST_URI'].'" method="POST" id="changeUsernameForm">
	      <input type="hidden" id="changeNonce" name="changeNonce" value="'.ulNonce::Create('change').'">
        <input type="hidden" id="action" name="action" value="changeUsername">
        <input type="hidden" id="idHidden" name="id" value="'.$id.'">
        <input type="hidden" id="currentUsernameHidden" name="currentUsername" value="'.$username.'">
        <h3 class="entry-title">Ändra användarnamn och/eller lösenord:</h3>
        <p class="italic">Att ändra användarnamn kräver att lösenordet också ändras</p>
        <label for="user">Nytt användarnamn:</label>
        <input type="text" name="username" id="usernameText" value="'.$username.'" onkeyup="checkField(this);" class="mandatory">
        <span id="usernameSpan" class="errorSpan">*</span>
        <label for="pwd">Nuvarande lösenord:</label>
        <input type="password" name="currentPassword" id="currentPasswordText" onkeyup="checkUsernameFields();" class="mandatory">
        <span id="currentPasswordSpan" class="errorSpan">*</span>
        <label for="user">Nytt lösenord:</label>
        <input type="password" name="newPassword" id="newPasswordText" onkeyup="checkField(this);" class="mandatory">
        <span id="newPasswordSpan" class="errorSpan">*</span>
        <label for="user">Nya lösenordet igen:</label>
        <input type="password" name="newPassword2" id="newPassword2Text" onkeyup="checkField(this);" class="mandatory">
        <span id="newPassword2Span" class="errorSpan">*</span>
        <label for="submit">Byt användaruppgifter:</label>
        <input type="submit" value="Byt!" id="submit" onclick="changeUsername(this);" disabled>
      </form>
    </div>
  ';
  return $content;
}

function showEditPlayer($dbh, $ulogin) {
  $player = getCurrentPlayer($dbh, $ulogin);
  if (!$player->mainPlayerId) {
    header('Location: '.__baseHref__.'/?s=anmal');
  }
  $content = submenu2($dbh, $ulogin, 'anmalda', false, $player);
  $content .= getUploadForm($dbh, $player, true, false);
  $content .= '
    <form id="newData" name="newData">
      <input type="hidden" name="newPhoto" id="newPhoto" value="false">
      <input type="hidden" name="loggedIn" id="loggedIn" value="true">
      <input type="hidden" name="baseHref" id="baseHref" value="'.__baseHref__.'">
      <input type="hidden" name="obj" id="obj" value="player">
      <input type="hidden" name="id" id="id" value="'.$player->id.'">
      <input type="hidden" name="user" id="user" value="'.$player->username.'">
      <div id="ifpaRegResults">
        <span id="playerLoading"><img src="'.__baseHref__.'/images/ajax-loader.gif" alt="Laddar data..."></span>
        <div id="ifpaRegResultsTableDiv" style="display: none">
          <h3 id="ifpaRegResultsH3">Hittade spelare:</h3>
          <table id="ifpaRegResultsTable" class="list">
          </table>
        </div>
      </div>
    </form>
    <div id="changeUser"><p><a href="'.__baseHref__.'/?s=andralogin">Ändra användarnamn eller lösenord</a></p></div>
  ';
  return $content;
}

$ulogin = new uLogin('appLogin', 'appLoginFail');

$ulogin->Autologin();

?>