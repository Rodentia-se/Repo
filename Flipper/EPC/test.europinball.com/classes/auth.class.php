<?php

  require_once(__ROOT__.'/contrib/ulogin/config/all.inc.php');
  require_once(__ROOT__.'/contrib/ulogin/main.inc.php');

  if (!sses_running()) {
    sses_start();
  }

  class auth extends uLogin {
    
    public function __construct($loginCallback = NULL, $loginFailCallback = NULL, $backend = NULL) {
/*
      $backends = array(
        'pdo' => 'ulPdoLoginBackend',
        'openid' => 'ulOpenIdLoginBackend',
        'duosec' => 'ulDuoSecLoginBackend',
        'ldap' => 'ulLdapLoginBackend',
        'ssh' => 'ulSsh2LoginBackend'
      );
      $backend = ($backends[$backend]) ? $backends[$backend] : config::$loginBackend;
      echo $backend;
      $this->Backend = new $backend();
*/
      parent::__construct($loginCallback, $loginFailCallback, $backend);
      $this->AutoLogin();
      if ($this->loggedin() && !$this->person) {
        $this->person = $this->getPerson();
        if ($this->person) {
          $this->person_id = $this->person->id;
        }
      }
    }
    
    public function getPerson() {
      if (isset($_SESSION['username']) && $_SESSION['username']) {
        return person(array('username' => $_SESSION['username']), TRUE);
      } else if ($this->Username($_SESSION['uid'])) {
        $_SESSION['username'] = $this->Username($_SESSION['uid']);
        if (isset($_SESSION['username']) && $_SESSION['username']) {
          return person(array('username' => $_SESSION['username']), TRUE);
        }
      }
      return FALSE;
    }

    public function login($username, $password, $nonce) {
      if (isset($nonce) && ulNonce::Verify('login', $nonce)) {
        $this->Authenticate($username, $password);
        if ($this->IsAuthSuccess()) {
          $_SESSION['uid'] = $this->AuthResult;
          $_SESSION['username'] = $this->Username($_SESSION['uid']);
          $_SESSION['loggedIn'] = TRUE;
          if (isset($_SESSION['appRememberMeRequested']) && ($_SESSION['appRememberMeRequested'] === TRUE)) {
            if (!$ulogin->SetAutologin($username, TRUE)) {
              warning('Could not turn on autologin');
            }
            unset($_SESSION['appRememberMeRequested']);
          } else {
            if (!$this->SetAutologin($username, FALSE)) {
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
        error('Invalid nonce: '.$nonce);
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

    public function action($action = NULL) {
      $action = ($action) ? $action : $_REQUEST['action'];
      switch ($action) {
        case 'login':
          debug("ACTION");
          if ($_REQUEST['username'] && $_REQUEST['password'] && $_REQUEST['nonce']) {
            debug("REQUEST");
            return $this->login($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['nonce']);
          } else {
            return FALSE;
          }
        break;
        case 'logout':
          return $this->logoff();
        break;
        case 'changeCredentials':
          if ($_REQUEST['currentUsername'] && $_REQUEST['currentPassword']) {
            $this->Authenticate($_REQUEST['currentUsername'], $_REQUEST['currentPassword']);
            if ($this->IsAuthSuccess()) {
              return $this->changeUser($_REQUEST['username'], $_REQUEST['newPassword']);
            } else {
              error('Could not login with your current credentials');
              return FALSE;
            }
          } else {
            error('Could not login with your current credentials');
            return FALSE;
          }
        break;
        case 'autologin':
          if (!$this->IsAuthSuccess()) {
            warning('Autologin misslyckades');
            return FALSE;
          } else {
            return TRUE;
          }
        break;
        default:
          return FALSE;
        break;
      }
    }

    public function loggedin() {
      return isset($_SESSION['uid']) && isset($_SESSION['username']) && isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] === TRUE);
    }

    public static function getLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL, $closeButton = FALSE) {
      debug($_SESSION);
      if (ulNonce::Exists('login')) {
        ulNonce::Verify('login', 'nonsense');
      }
      $nonce = ulNonce::Create('login');
      return '
        <div id="'.$prefix.'loginDiv" class="loginDiv '.$class.'">
          '.(($closeButton) ? '<img src="'.config::$baseHref.'/images/cancel.png" id="'.$prefix.'closeLoginDiv" class="right textIcon" alt="Click to close the box" title="Close">' : '').'
        	<h2 class="loginTitle">'.$title.'</h2>
          <form action="'.$_SERVER['REQUEST_URI'].'" method="POST" id="'.$prefix.'loginForm">
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="baseHref" id="'.$prefix.'baseHref" value="'.config::$baseHref.'">
            <input type="hidden" name="nonce" id="'.$prefix.'nonce" value="'.$nonce.'">
            <div id="usernameDiv">
              <label for="username">Username:</label>
              <input type="text" name="username" id="'.$prefix.'usernameLogin" class="mandatory" onkeyup="login(this);" onchange="login(this);">
              <span id="'.$prefix.'usernameLoginSpan" class="errorSpan">*</span>
            </div>
            <div id="passwordDiv">
              <label for="password">Password:</label>
              <input type="password" name="password" id="'.$prefix.'passwordText" class="mandatory" onkeyup="login(this);" onchange="login(this);">
              <span id="'.$prefix.'passwordSpan" class="errorSpan">*</span>
            </div>
            <div id="'.$prefix.'autologinDiv">
              <label for="autologin" class="infoLabel">Remember me:</label>
              <input type="checkbox" name="autologin" value="1" id="'.$prefix.'autologinCheckbox">
            </div>
            <div id="'.$prefix.'loginButtonDiv">
              <input type="submit" value="Log in" id="'.$prefix.'loginButton" onclick="login(this);">&nbsp;&nbsp;
              <a href="'.config::$baseHref.'/login/?action=reset" class="italic">Forgot username or password?</a>
            </div>
  	      </form>
        </div>
        '.page::getScript("
          $('#".$prefix."closeLoginDiv').click(function() {
            $('#".$prefix."loginDiv').hide();
          });
        ", TRUE).'
      ';
    }

  }

?>