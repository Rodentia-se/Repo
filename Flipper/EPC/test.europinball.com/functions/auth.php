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
          echo('Password changed for '.$newUser.'<br />');
          return true;
        } else {
          echo('Password changed for '.$newUser.' failed<br />');
          return false;
        }
      } else {
        if (updateUser($dbh, $player, $ulogin)) {
          if ($ulogin->DeleteUser($_SESSION['uid'])) {
            appLogout($ulogin);
            $ulogin->Authenticate($newUser,  $newPass);
            if ($ulogin->IsAuthSuccess()) {
              echo('Logged in as '.$newUser.'<br />');
              return true;
            } else {
              echo('Could not login as '.$newUser.'<br />');
              return false;
            }
          } else {
            echo('Old account deletion failure<br />');
            return false;
          }
        } else {
          echo('New account creation failure<br />');
          return false;
        }
      }
    } else {
      echo('invalid nonce: '.$_POST['changeNonce'].'<br />');
      return false;
    }
  } else {
    echo('You need to login first<br />');
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
			echo "cannot enable autologin<br>";
    }
		unset($_SESSION['appRememberMeRequested']);
	}	else {
		// Disable remember-me
		if ( !$ulogin->SetAutologin($username, false)) {
			echo 'cannot disable autologin<br>';
    }
	}
}

function appLoginFail($uid = null, $username = null, $ulogin = null) {
	// Note, in case of a failed login, $uid, $username or both
	// might not be set (might be NULL).
	$response = json_decode(getError('Login failed for '.$username, false))->reason;
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

function checkLogin($dbh, $ulogin, $req = true, $title = 'You need to login to access this page') {
  $action = @$_REQUEST['action'];
  $msg = null;
  if (isAppLoggedIn()) {
    if ($action == 'logout') { // We've been requested to log out
      appLogout($ulogin);
    } else if ($action == 'changeUsername') {
      echo('Changing credentials...<br />');
      $ulogin->Authenticate($_REQUEST['currentUsername'], $_REQUEST['currentPassword']);
      if ($ulogin->IsAuthSuccess()) {
        changeUser($dbh, $ulogin, $_REQUEST['username'], $_REQUEST['newPassword']);
      } else {
        echo('Wrong credentials for current login<br />');
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
        $msg = 'invalid nonce: '.$_POST['nonce'];
      }
    } else if ($action == 'autologin'){	// We were requested to use the remember-me function for logging in.
      // Note, there is no username or password for autologin ('remember me')
      if (!$ulogin->IsAuthSuccess()) {
        $msg = 'autologin failure';
      } else {
        $msg = 'autologin ok';
      }
    }
  }
  if (!isAppLoggedIn()) {
    if ($req == true) {
      printTopper();
      echo($msg);
      echo(showLogin($ulogin, $title));
    }
    return false;
  } else {
    return true;
  }
}

function showLogin($ulogin, $title = 'You need to login to access this page') {
  $content = '
  	<h3>'.$title.'</h3>
    <script type="text/javascript">
      $(document).ready(function() {
        setTimeout(function() {
          if (document.getElementById(\'usernameLogin\').value != \'\') {
            document.getElementById(\'loginButton\').disable = false;
          }
        }, 500);
      }); 
    </script>
  	<form action="'.$_SERVER['REQUEST_URI'].'" method="POST" id="loginForm">
      <input type="hidden" name="action" value="login">
      <input type="hidden" name="loggedIn" id="loggedIn" value="false">
      '.(($_REQUEST['obj']) ? '<input type="hidden" name="obj" id="obj" value="'.$_REQUEST['obj'].'">' : '').'
      '.(($_REQUEST['id']) ? '<input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'">' : '').'
      <input type="hidden" name="baseHref" id="baseHref" value="'.__baseHref__.'">
			<input type="hidden" id="nonce" name="nonce" value="'.ulNonce::Create('login').'">
      <table>
        <tr>
          <td><label for="username">Username:</label></td>
  		    <td><input type="text" name="username" id="usernameLogin" class="mandatory" onkeyup="login(this);"></td>
          <td><span id="usernameLoginSpan" class="errorSpan">*</span></td>
        </tr>
        <tr>
          <td><label for="password">Password:</label></td>
          <td><input type="password" name="password" id="passwordText" class="mandatory" onkeyup="login(this);"></td>
          <td><span id="passwordSpan" class="errorSpan">*</span></td>
        </tr>
        <tr>
          <td><label for="autologin>Remember me:</label></td>
          <td><input type="checkbox" name="autologin" value="1" id="autologinCheckbox"></td>
        </tr>
        <tr>
  		    <td><input type="submit" value="Login" id="loginButton" onclick="login(this);" disabled></td>
          <td><a href="'.__baseHref__.'/your-pages/password-reset/" class="italic">Forgot  password?</a></td>
        </tr>
      </table>
  	</form>
  ';
  return $content;
}

function showChangeUsername($dbh, $username) {
  $username = $_SESSION['username'];
  $id = getIdFromUser($dbh, $username);
  $content = '
    <form action="'.$_SERVER['REQUEST_URI'].'" method="POST" id="changeUsernameForm">    
	    <input type="hidden" id="changeNonce" name="changeNonce" value="'.ulNonce::Create('change').'">
      <input type="hidden" id="action" name="action" value="changeUsername">
      <input type="hidden" id="idHidden" name="id" value="'.$id.'">
      <input type="hidden" id="currentUsernameHidden" name="currentUsername" value="'.$username.'">
      <h3 class="entry-title">Change username and/or password:</h3>
      <span class="italic">Changing username requires setting a new password</span>
      <table>
        <tr>
          <td><label for="user">New username:</label></td>
          <td><input type="text" name="username" id="usernameText" value="'.$username.'" onkeyup="checkField(this);" class="mandatory"></td>
          <td><span id="usernameSpan" class="errorSpan">*</span></td>
        </tr>
        <tr>
          <td><label for="pwd">Current password:</label></td>
          <td><input type="password" name="currentPassword" id="currentPasswordText" onkeyup="checkUsernameFields();" class="mandatory"></td>
          <td><span id="currentPasswordSpan" class="errorSpan">*</span></td>
        </tr>
        <tr>
          <td><label for="user">New password:</label></td>
          <td><input type="password" name="newPassword" id="newPasswordText" onkeyup="checkField(this);" class="mandatory"></td>
          <td><span id="newPasswordSpan" class="errorSpan">*</span></td>
        </tr>
        <tr>
          <td><label for="user">Retype new password:</label></td>
          <td><input type="password" name="newPassword2" id="newPassword2Text" onkeyup="checkField(this);" class="mandatory"></td>
          <td><span id="newPassword2Span" class="errorSpan">*</span></td>
        </tr>
        <tr>
          <td><label for="submit">Change credentials:</label></td>
          <td><input type="submit" value="Change" id="submit" onclick="changeUsername(this);" disabled></td>
          <td></td>
        </tr>
      </table>
    </form>
  ';
  return $content;
}

function showEditPlayer($dbh, $ulogin) {
  $player = getCurrentPlayer($dbh, $ulogin);
  $content = '    <h2 class="entry-title">Edit player profile</h2>';
  $content .=  getUploadForm($dbh, $player, true, false);
  $content .= '
    <form id="newData" name="newData">
      <input type="hidden" name="newPhoto" id="newPhoto" value="false">
      <input type="hidden" name="loggedIn" id="loggedIn" value="true">
      <input type="hidden" name="baseHref" id="baseHref" value="'.__baseHref__.'">
      <input type="hidden" name="obj" id="obj" value="player">
      <input type="hidden" name="id" id="id" value="'.$player->id.'">
      <input type="hidden" name="user" id="user" value="'.$player->username.'">
      <div id="ifpaRegResults">
        <span id="playerLoading"><img src="'.__baseHref__.'/images/ajax-loader.gif" alt="Loading data..."></span>
        <div id="ifpaRegResultsTableDiv" style="display: none">
          <h3 id="ifpaRegResultsH3">People found:</h3>
          <table id="ifpaRegResultsTable" class="list">
          </table>
        </div>
      </div>
    </form>
    <div id="changeUser"><a href="'.__baseHref__.'/your-pages/change-credentials/">Change username or password</a></div>
    <form action="'.$_SERVER['REQUEST_URI'].'" method="POST">
      <input type="hidden" name="action" value="logout">
      <input type="submit" value="Logout">
    </form>
  ';
  return $content;
}

$ulogin = new uLogin('appLogin', 'appLoginFail');

$ulogin->Autologin();

?>