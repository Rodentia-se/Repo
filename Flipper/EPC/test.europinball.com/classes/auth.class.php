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
        error('Invalid nonce: '.$nonce.', please clean cache and cookies and try again.');
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
          if ($_REQUEST['username'] && $_REQUEST['password'] && $_REQUEST['nonce']) {
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
            warning('Autologin failed');
            return FALSE;
          } else {
            return TRUE;
          }
        break;
        case 'newUser':
          if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['nonce']) && isId($_REQUEST['person_id'])) {
            if (ulNonce::Verify('login', $_REQUEST['nonce'])) {
              $person = person($_REQUEST['person_id']);
              if ($person) {
                if ($this->CreateUser($_REQUEST['username'],  $_REQUEST['password'])) {
                  if($person->setUsername($_REQUEST['username'])) {
                    return TRUE;
                  } else {
                    error('User created, but could not associate the user with the person');
                  }
                } else {
                  error('Could not create user '.$_REQUEST['username']);
                }
              } else {
                error('Could not find person ID '.$_REQUEST['person_id']);
              }
            } else {
              error('Invalid nonce '.$_REQUEST['nonce'].', please clean cache and cookies and try again.');
            }
          } else {
            error('Not enough parameters provided');
          }
          return FALSE;
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
      $form = page::getDivStart($prefix.'loginDiv', $class, (($dialog) ? 'title="'.$title : NULL));
        $form .= (!$dialog) ? page::getH4($title) : '';
        $form .= page::getFormStart($prefix.'loginForm');
          $form .= '<fieldset>';
            $form .= page::getInput('login', $prefix.'action', 'action', 'hidden');
            $form .= page::getInput(self::$nonce, $prefic.'nonce', 'nonce', 'hidden');
            $form .= page::getInput('', $prefix.'username', 'username', 'text', 'enterSubmit');
            $form .= page::getInput('', $prefic.'password', 'password', 'password', 'enterSubmit');
            $form .= page::getInput(TRUE, $prefix.'autologin', 'autologin', 'checkbox', NULL, 'Remember me');
            $form .= ($dialog) ? page::getButton('Login', $prefix.'loginButton') : '';
          $form .= '</fieldset>';
        $form .= page::getFormEnd();
        $form .= page::getClickButton('I forgot all this!', $prefix.'forgotButton');
        $form .= page::getScript('
          $("#'.$prefix.'forgotButtonForm").append("<input type=\"hidden\" name=\"action\" value=\"reset\">");
        ');
      $form .= page::getDivEnd();
      if ($dialog) {
        $form .= page::getScript('
          $("#'.$prefix.'loginDiv").dialog({
            autoOpen: '.(($autoopen) ? 'true' : 'false').',
            modal: true,
            width: 400,
            buttons: {
              "Login": function() {
                if ($.trim($("#'.$prefix.'username").val()).length > 0 && $.trim($("#'.$prefix.'password").val()).length > 0) {
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
              if ($.trim($("#'.$prefix.'username").val()).length > 0 && $.trim($("#'.$prefix.'password").val()).length > 0) {
                $("#'.$prefix.'loginForm").submit();
              }
            }
          });
          $(document).on("click", ".ui-widget-overlay", function() {
            $("#'.$prefix.'loginDiv").dialog("close");
          });
          $("#'.$prefix.'forgotButton").click(function() {
            $("#'.$prefix.'forgotButtonForm").submit();
          });
        ');
      }
      return $form;
    }

    public static function getNewUser($title = 'Please choose a new username and password', $person_id, $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      $form = '
        <div id="'.$prefix.'newUserDiv" '.(($dialog) ? 'title="'.$title.'">' : '>
        	<h2>'.$title.'</h2>').'
          <form id="'.$prefix.'newUserForm" method="POST">
            <fieldset>
              <input type="hidden" name="action" value="newUser">
              <input type="hidden" name="person_id" value="'.$person_id.'">
              <input type="hidden" name="nonce" value="'.self::$nonce.'">
              <label for="username">Username</label>
              <input type="text" name="username" id="'.$prefix.'usernameNew" class="text ui-widget-content ui-corner-all enterSubmit"><br />
              <label for="password">Password</label>
              <input type="password" name="password" id="'.$prefix.'passwordNew" class="text ui-widget-content ui-corner-all enterSubmit"><br />
              '.(($dialog) ? '' : '<input type="submit" value="Register">').'
            </fieldset>
          </form>
        </div>
      ';
      if ($dialog) {
        $form .= page::getScript('
          $("#'.$prefix.'newUserDiv").dialog({
            autoOpen: '.(($autoopen) ? 'true' : 'false').',
            modal: true,
            width: 400,
            buttons: {
              "Register": function() {
                if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
                  $("#'.$prefix.'newUserForm").submit();
                }
              },
              "Cancel": function() {
                $(this).dialog("close");
              }
            }
          });
          $(".enterSubmit").keypress(function(e) {
            if (e.keyCode == $.ui.keyCode.ENTER) {
              if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
                $("#'.$prefix.'newUserForm").submit();
              }
            }
          });
          $(document).on("click", ".ui-widget-overlay", function() {
            $("#'.$prefix.'newUserDiv").dialog("close");
          });
        ');
      }
      return $form;
    }
  }

?>