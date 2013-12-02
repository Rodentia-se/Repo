<?php

  require_once(__ROOT__.'/contrib/ulogin/config/all.inc.php');
  require_once(__ROOT__.'/contrib/ulogin/main.inc.php');

  if (!sses_running()) {
    sses_start();
  }

  class auth extends uLogin {
    
    private static $nonce;
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
      if ($_REQUEST['action']) {
        $this->action($_REQUEST['action']);
      }
      if ($this->loggedin() && !$this->person) {
        $this->person = $this->getPerson();
        if ($this->person) {
          $this->person_id = $this->person->id;
        }
      } else {
        if (ulNonce::Exists('login')) {
          ulNonce::Verify('login', 'nonsense');
        }
        self::$nonce = ulNonce::Create('login');
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

    public static function getLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      $form = '
        <div id="'.$prefix.'loginDiv" '.(($dialog) ? 'title="'.$title.'">' : '>
        	<h2>'.$title.'</h2>').'
          <form id="'.$prefix.'loginForm" action="'.$_SERVER['REQUEST_URI'].'" method="POST">
            <fieldset>
              <input type="hidden" name="action" value="login">
              <input type="hidden" name="nonce" value="'.self::$nonce.'">
              <label for="username">Username</label>
              <input type="text" name="username" id="'.$prefix.'usernameLogin" class="text ui-widget-content ui-corner-all enterSubmit"><br />
              <label for="password">Password</label>
              <input type="password" name="password" id="'.$prefix.'passwordText" class="text ui-widget-content ui-corner-all enterSubmit"><br />
              <label for="autologin">
                <input type="checkbox" name="autologin" value="1" id="'.$prefix.'autologinCheckbox"> Remember me
              </label><br />
              '.(($dialog) ? '' : '<input type="submit" value="Log in">').'
              <p><a href="'.config::$baseHref.'/login/?action=reset" class="italic">Forgot username or password?</a></p>
            </fieldset>
          </form>
        </div>
      ';
      if ($dialog) {
        $form .= page::getScript('
          $("#'.$prefix.'loginDiv").dialog({
            autoOpen: '.(($autoopen) ? 'true' : 'false').',
            modal: true,
            width: 400,
            buttons: {
              "Login": function() {
                if ($.trim($("#'.$prefix.'usernameLogin").val()).length > 0 && $.trim($("#'.$prefix.'passwordText").val()).length > 0) {
                  $("#'.$prefix.'loginForm").submit();
                }
              },
              "Cancel": function() {
                $(this).dialog("close");
              }
            }
          });
          $(".enterSubmit").keypress(function(e) {
            if (e.keyCode == $.ui.keyCode.ENTER) {
              if ($.trim($("#'.$prefix.'usernameLogin").val()).length > 0 && $.trim($("#'.$prefix.'passwordText").val()).length > 0) {
                $("#'.$prefix.'loginForm").submit();
              }
            }
          });
          $(document).on("click", ".ui-widget-overlay", function() {
            $("#'.$prefix.'loginDiv").dialog("close");
          });
        ', TRUE);
      }
      return $form;
    }

  }

?>