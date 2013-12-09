<?php

  require_once(__ROOT__.'/contrib/ulogin/config/all.inc.php');
  require_once(__ROOT__.'/contrib/ulogin/main.inc.php');

  if (!sses_running()) {
    sses_start();
  }

  class auth extends uLogin {
    
    private static $nonce;
    public static $person;
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
      if ($this->loggedin() && !self::$person) {
        if (isset($_SESSION['username']) && $_SESSION['username']) {
          self::$person = person(array('username' => $_SESSION['username']), TRUE);
        } else if ($this->Username($_SESSION['uid'])) {
          $_SESSION['username'] = $this->Username($_SESSION['uid']);
          if (isset($_SESSION['username']) && $_SESSION['username']) {
            self::$person = person(array('username' => $_SESSION['username']), TRUE);
          }
        }
        self::$person = $this->getPerson();
      } else {
        if (!self::$nonce || !ulNonce::Exists('login')) {
          self::$nonce = ulNonce::Create('login');
        }
      }
    }
    
    public function setLogin($login = TRUE) {
      if ($login) {
        $_SESSION['uid'] = $this->AuthResult;
        $_SESSION['username'] = $this->Username($_SESSION['uid']);
        $_SESSION['loggedIn'] = TRUE;
      } else {
        unset($_SESSION['uid']);
        unset($_SESSION['username']);
        unset($_SESSION['loggedIn']);
      }
    }

    public function login($username, $password, $nonce) {
      if (isset($nonce) && ulNonce::Verify('login', $nonce)) {
        $this->Authenticate($username, $password);
        if ($this->IsAuthSuccess()) {
          $this->setLogin();
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
          self::$person = $this->getPerson();
          if (self::$person) {
            return TRUE;
          } else {
            error('Login successful, but could not find you in the database');
            return FALSE;
          }
        } else {
          $this->setLogin(FALSE);
          error('Login failed');
          return FALSE;
        }
      } else {
        error('Invalid nonce: '.$nonce.', please clean cache and cookies and try again.');
        return FALSE;
      }
    }
    
    protected function changeUser($username, $password, $person) {
      if ($person) {
        $uid = $person->getUid();
        if ($uid) {
          if ($username == $_SESSION['username']) {
            if ($this->SetPassword($uid, $password)) {
              $this->Authenticate($username, $password);
              if ($ulogin->IsAuthSuccess()) {
                $this->setLogin();
                return TRUE;
              } else {
                $this->setLogin(FALSE);
                error('Password changed, but could not log you in. Please try logging in.');
              }
            } else {
              error('Could not change password for '.$username.', your login has not been changed.');
            }
          } else if ($this->addUser($username, $password, $person)) {
            $this->Authenticate($username, $password);
            if ($ulogin->IsAuthSuccess()) {
              $this->setLogin();
              return TRUE;
            } else {
              $this->setLogin(FALSE);
              error('User created, but could not log you in. Please try logging in.');
            }
          } else {
            error('Could not add the user');
          }
        } else {
          error('Could not identify you, please logout and login again.');
        }
      } else {
        error('Could not identify you, please logout and login again.');
      }
      return FALSE;
    }
    
    protected function addUser($username, $password, $person = NULL);
      if ($this->CreateUser($_REQUEST['username'],  $_REQUEST['password'])) {
        if ($person) {
          if($person->setUsername($_REQUEST['username'])) {
            return TRUE;
          } else {
            error('User created, but could not associate the user with the person');
          }
        } else {
          return TRUE;
        }
      } else {
        error('Could not create user '.$_REQUEST['username']);
      }
      return  FALSE;
    }

    public function logoff() {
      $this->SetAutologin($_SESSION['username'], FALSE);
      unset($_SESSION['uid']);
      unset($_SESSION['username']);
      unset($_SESSION['loggedIn']);
      self::$person = NULL;
      return TRUE;
    }

    public function action($action = NULL) {
      $action = ($action) ? $action : $_REQUEST['action'];
      switch ($action) {
        case 'login':
          if ($_REQUEST['username'] && $_REQUEST['password'] && $_REQUEST['nonce']) {
            return $this->login($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['nonce']);
          } else {
            error('Could not log you in');
            return FALSE;
          }
        break;
        case 'logout':
          return $this->logoff();
        break;
        case 'changeUser':
          if ($_SESSION['username'] && $_REQUEST['password'] && $_REQUEST['nonce']) {
            if ($this->login($_SESSION['username'], $_REQUEST['password'], $_REQUEST['nonce'])) {
              if ($_REQUEST['newPassword'] == $_REQUEST['verifyPassword']) {
                return $this->changeUser($_REQUEST['newUsername'], $_REQUEST['newPassword']);
              } else {
                error('The password did not match, please try again');
              }
            } else {
              error('Could not login with your current credentials');
            }
          } else {
            error('Could not login with your current credentials');
          }
          return FALSE;
        break;
        case 'autologin':
          if (!$this->IsAuthSuccess()) {
            $this->setLogin(FALSE);
            warning('Autologin failed');
            return FALSE;
          } else {
            $this->setLogin();
            return TRUE;
          }
        break;
        case 'newUser':
          if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['nonce']) && isId($_REQUEST['person_id'])) {
            if (ulNonce::Verify('login', $_REQUEST['nonce'])) {
              $person = person($_REQUEST['person_id']);
              if ($person) {
                if ($this->addUser($_REQUEST['username'], $_REQUEST['password'], $person)) {
                  $this->Authenticate($_REQUEST['username'], $_REQUEST['password']);
                  if ($ulogin->IsAuthSuccess()) {
                    $this->setLogin();
                    return TRUE;
                  } else {
                    $this->setLogin(FALSE);
                    error('User created, but could not log you in. Please try logging in.');
                  }
                } else {
                  error('Could not add the user');
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
      if (isset($_SESSION['uid']) && isset($_SESSION['username']) && isset($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] === TRUE)) {
        if (isObj(auth::$person) && isId(auth::$person->id)) {
          return auth::$person;
        } else {
          return TRUE;
        }
      } else {
        return FALSE;
      }
    }

    public static function getLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      $form = page::getDivStart($prefix.'loginDiv', $class, (($dialog) ? $title : NULL));
        $form .= (!$dialog) ? page::getH4($title) : '';
        $form .= page::getFormStart($prefix.'loginForm');
          $form .= '<fieldset>';
            $form .= page::getInput('login', $prefix.'action', 'action', 'hidden');
            $form .= page::getInput(self::$nonce, $prefix.'nonce', 'nonce', 'hidden');
            $form .= page::getDivStart($prefix.'usernameDiv');
              $form .= page::getInput('', $prefix.'username', 'username', 'text', 'enterSubmit');
            $form .= page::getDivEnd();
            $form .= page::getDivStart($prefix.'passwordDiv');
              $form .= page::getInput('', $prefix.'password', 'password', 'password', 'enterSubmit');
            $form .= page::getDivEnd();
            $form .= page::getDivStart($prefix.'autologinDiv');
              $form .= page::getLabel(' ').page::getInput(TRUE, $prefix.'autologin', 'autologin', 'checkbox', NULL, 'Remember me');
            $form .= page::getDivEnd();
          $form .= '</fieldset>';
        $form .= page::getFormEnd();
        $form .= page::getLabel(' ');
        $form .= (!$dialog) ? page::getButton('Login', $prefix.'login') : '';
        $form .= page::getButton('I forgot all this!', $prefix.'reset');
        $form .= page::getFormStart($prefix.'resetForm');
          $form .= page::getInput('reset', $prefix.'action', 'action', 'hidden');
        $form .= page::getFormEnd();
        $form .= page::getScript('
          $("#'.$prefix.'resetButton").click(function() {
            $("#'.$prefix.'resetForm").submit();
          });
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
          $(document).on("click", ".ui-widget-overlay", function() {
            $("#'.$prefix.'loginDiv").dialog("close");
          });
        ');
      }
      $form .= page::getScript('
        $(".enterSubmit").keypress(function(e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            if ($.trim($("#'.$prefix.'username").val()).length > 0 && $.trim($("#'.$prefix.'password").val()).length > 0) {
              $("#'.$prefix.'loginForm").submit();
            }
          }
        });
        $("#'.$prefix.'loginButton").click(function() {
          $("#'.$prefix.'loginForm").submit();
        });
      ');
      return $form;
    }
    
    public static function getUserEdit($title = 'Change credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      $form = page::getDivStart($prefix.'changeUserDiv');
        $form .= page::getFormStart($prefix.'changeUserForm');
          $form .= page::getH2($title);
          $form .= page::getParagraph('Changing username requires changing the password too.', NULL, 'italic');
          $form .= page::getInput(self::$nonce, $prefix.'nonce', 'nonce', 'hidden');
          $form .= page::getInput('changeUser', $prefix.'action', 'action', 'hidden');
          $form .= page::getDivStart($prefix.'usernameDiv');
            $form .= page::getInput($_SESSION['username'], $prefix.'newUsername', 'newUsername', 'text', NULL, 'New username');
          $form .= page::getDivEnd();
          $form .= page::getDivStart($prefix.'passwordDiv');
            $form .= page::getInput(NULL, $prefix.'password', 'password', 'password', NULL, 'Current password');
          $form .= page::getDivEnd();
          $form .= page::getDivStart($prefix.'newPasswordDiv');
            $form .= page::getInput(NULL, $prefix.'newPassword', 'newPassword', 'password', NULL, 'New password');
          $form .= page::getDivEnd();
          $form .= page::getDivStart($prefix.'verifyPasswordDiv');
            $form .= page::getInput(NULL, $prefix.'verifyPassword', 'verifyPassword', 'password', NULL, 'Verify new password');
          $form .= page::getDivEnd();
          $form .= (!$dialog) ? page::getLabel('&nbsp').page::getButton('Submit changes', $prefix.'changeUser') : '';
        $form .= page::getFormEnd();
      $form .= page::getDivEnd();
      if ($dialog) {
        $form .= page::getScript('
          $("#'.$prefix.'newUserDiv").dialog({
            autoOpen: '.(($autoopen) ? 'true' : 'false').',
            modal: true,
            width: 400,
            buttons: {
              "Register": function() {
                if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
                  if ($("#'.$prefix.'passwordNew").val() == $("#'.$prefix.'verifyPasswordNew").val()) {
                    $("#'.$prefix.'newUserForm").submit();
                  } else {
                    $("#'.$prefix.'verifyPasswordNew").tooltipster({
                      theme: ".tooltipster-light",
                      content: "The passwords do not match...",
                      trigger: "custom",
                      position: "right",
                      timer: 3000
                    })
                    .tooltipster("show");
                  }
                }
              },
              "Cancel": function() {
                $(this).dialog("close");
              }
            }
          });
          $(document).on("click", ".ui-widget-overlay", function() {
            $("#'.$prefix.'newUserDiv").dialog("close");
          });
        ');
      } else {
        $form .= page::getScript('
          $("'.$prefix.'changeUserButton").click(function() {
            if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
              if ($("#'.$prefix.'passwordNew").val() == $("#'.$prefix.'verifyPasswordNew").val()) {
                $("#'.$prefix.'newUserForm").submit();
              } else {
                $("#'.$prefix.'verifyPasswordNew").tooltipster({
                  theme: ".tooltipster-light",
                  content: "The passwords do not match...",
                  trigger: "custom",
                  position: "right",
                  timer: 3000
                })
                .tooltipster("show");
              }
            }
          });
        ');
      }
      $form .= page::getScript('
        $(".enterSubmit").keypress(function(e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
              if ($("#'.$prefix.'passwordNew").val() == $("#'.$prefix.'verifyPasswordNew").val()) {
                $("#'.$prefix.'newUserForm").submit();
              } else {
                $("#'.$prefix.'verifyPasswordNew").tooltipster({
                  theme: ".tooltipster-light",
                  content: "The passwords do not match...",
                  trigger: "custom",
                  position: "right",
                  timer: 3000
                })
                .tooltipster("show");
              }
            }
          }
        });
      ');
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
              <label for="password">Verify password</label>
              <input type="password" name="verifyPassword" id="'.$prefix.'verifyPasswordNew" class="text ui-widget-content ui-corner-all enterSubmit"><br />
              '.(($dialog) ? '' : '<input type="button" id="'.$prefix.'registerButton" value="Register">').'
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
                  if ($("#'.$prefix.'passwordNew").val() == $("#'.$prefix.'verifyPasswordNew").val()) {
                    $("#'.$prefix.'newUserForm").submit();
                  } else {
                    $("#'.$prefix.'verifyPasswordNew").tooltipster({
                      theme: ".tooltipster-light",
                      content: "The passwords do not match...",
                      trigger: "custom",
                      position: "right",
                      timer: 3000
                    })
                    .tooltipster("show");
                  }
                }
              },
              "Cancel": function() {
                $(this).dialog("close");
              }
            }
          });
          $(document).on("click", ".ui-widget-overlay", function() {
            $("#'.$prefix.'newUserDiv").dialog("close");
          });
        ');
      } else {
        $form .= page::getScript('
          $("'.$prefix.'registerButton").click(function() {
            if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
              if ($("#'.$prefix.'passwordNew").val() == $("#'.$prefix.'verifyPasswordNew").val()) {
                $("#'.$prefix.'newUserForm").submit();
              } else {
                $("#'.$prefix.'verifyPasswordNew").tooltipster({
                  theme: ".tooltipster-light",
                  content: "The passwords do not match...",
                  trigger: "custom",
                  position: "right",
                  timer: 3000
                })
                .tooltipster("show");
              }
            }
          });
        ');
      }
      $form .= page::getScript('
        $(".enterSubmit").keypress(function(e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            if ($.trim($("#'.$prefix.'usernameNew").val()).length > 0 && $.trim($("#'.$prefix.'passwordNew").val()).length > 0) {
              if ($("#'.$prefix.'passwordNew").val() == $("#'.$prefix.'verifyPasswordNew").val()) {
                $("#'.$prefix.'newUserForm").submit();
              } else {
                $("#'.$prefix.'verifyPasswordNew").tooltipster({
                  theme: ".tooltipster-light",
                  content: "The passwords do not match...",
                  trigger: "custom",
                  position: "right",
                  timer: 3000
                })
                .tooltipster("show");
              }
            }
          }
        });
      ');
      return $form;
    }

  }

?>