<?php

  require_once(__ROOT__.'/contrib/ulogin/config/all.inc.php');
  require_once(__ROOT__.'/contrib/ulogin/main.inc.php');

  if (!sses_running()) {
    sses_start();
  }

  class auth extends uLogin {
    
    public function __construct($loginCallback = NULL, $loginFailCallback = NULL, $backend = NULL) {
      $backends = array(
        'pdo' => 'ulPdoLoginBackend',
        'openid' => 'ulOpenIdLoginBackend',
        'duosec' => 'ulDuoSecLoginBackend',
        'ldap' => 'ulLdapLoginBackend',
        'ssh' => 'ulSsh2LoginBackend'
      );
      $backend = ($backends[$backend]) ? $backends[$backend] : config::$loginBackend;
      parent::__construct($loginCallback, $loginFailCallback, $backend);
      $this->AutoLogin();
      $this->person = $this->getPerson();
      if ($this->person) {
        $this->person_id = $this->person->id;
      }
    }
    
    public function getPerson() {
      if ($_SESSION['username']) {
        return new person(array('username' => $_SESSION['username']));
      } else {
        return FALSE;
      }
    }

    public function login($username, $password, $nonce) {
      if (isset($nonce) && ulNonce::Verify('login', $nonce)) {
        $this->Authenticate($username, $password);
        if ($this->IsAuthSuccess()) {
          $_SESSION['uid'] = $this->AuthResult;
          $_SESSION['username'] = $this->Username($uid);
          $_SESSION['loggedIn'] = TRUE;
          if (isset($_SESSION['appRememberMeRequested']) && ($_SESSION['appRememberMeRequested'] === TRUE)) {
            if (!$ulogin->SetAutologin($username, TRUE)) {
              warning('Could not turn on autologin');
            }
            unset($_SESSION['appRememberMeRequested']);
          } else {
            if (!$ulogin->SetAutologin($username, FALSE)) {
              warning('Could not turn off autologin');
            }
          }
          $this->person = $this->getPerson();
          if ($this->person) {
            $this->person_id = $this->person->id;
            return TRUE;
          } else {
            error('Login successful, but could not find you in the database');
            return FALSE;
          }
        } else {
          error('Login failed');
          return FALSE;
        }
      } else {
        $msg = 'Felaktig nonce: '.$nonce;
        return FALSE;
      }
    }

    public function logoff() {
      $this->SetAutologin($_SESSION['username'], FALSE);
      unset($_SESSION['uid']);
      unset($_SESSION['username']);
      unset($_SESSION['loggedIn']);
      return TRUE;
    }

    public function action() {
      $action = @$_REQUEST['action'];
      switch ($action) {
        case 'login':
          return $this->login($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['nonce']);
        break;
        case 'logout':
          return $this->logoff();
        break;
        case 'changeCredentials':
          $ulogin->Authenticate($_REQUEST['currentUsername'], $_REQUEST['currentPassword']);
          if ($ulogin->IsAuthSuccess()) {
            return $this->changeUser($_REQUEST['username'], $_REQUEST['newPassword']);
          } else {
            error('Could not login with your current credentials');
            return FALSE;
          }
        break;
        case 'autologin':
          if (!$ulogin->IsAuthSuccess()) {
            $msg = 'Autologin misslyckades';
          } else {
            $msg = 'Autologin ok';
          }
        break;
      }
    }

    public static function checkLogin() {
      return isset($_SESSION['uid']) && isset($_SESSION['username']) && isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] === TRUE);
    }

    public static function reqLogin($title = 'Please provide your login credentials') {
      $action = @$_REQUEST['loginAction'];
      if ($this->checkLogin()) {
        return TRUE;
      } else {
        return $this->getLogin($title);
      }
    }

    public function getLogin($title = 'Please provide your login credentials') {
      return '
        <div id="loginDiv" class="loginDiv">
          <form action="'.$_SERVER['REQUEST_URI'].'" method="POST" id="loginForm">
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="loggedIn" id="loggedIn" value="false">
            <input type="hidden" name="baseHref" id="baseHref" value="'.config::$baseHref.'">
			      <input type="hidden" name="nonce" id="nonce" value="'.ulNonce::Create('login').'">
            <div id="usernameDiv">
              <label for="username">Användarnamn:</label>
  		        <input type="text" name="username" id="usernameLogin" class="mandatory" onkeyup="login(this);" onchange="login(this);">
              <span id="usernameLoginSpan" class="errorSpan">*</span>
            </div>
            <div id="passwordDiv">
              <label for="password">Lösenord:</label>
              <input type="password" name="password" id="passwordText" class="mandatory" onkeyup="login(this);" onchange="login(this);">
              <span id="passwordSpan" class="errorSpan">*</span>
            </div>
            <div id="autologinDiv">
              <label for="autologin" class="infoLabel">Remember me:</label>
              <input type="checkbox" name="autologin" value="1" id="autologinCheckbox">
            </div>
            <div id="loginDiv">
    		      <input type="submit" value="Logga in" id="loginButton" onclick="login(this);" disabled>&nbsp;&nbsp;
              <a href="'.config::$baseHref.'/?s=losenreset" class="italic">Glömt lösenordet?</a>
            </div>
  	      </form>
        </div>
      ';
    }

  }

?>