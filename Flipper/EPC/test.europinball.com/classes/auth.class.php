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

    public function login($username, $password) {
      $this->Authenticate($username, $password);
      if ($this->IsAuthSuccess()) {
        $this->setLogin();
        if (isset($_SESSION['appRememberMeRequested']) && ($_SESSION['appRememberMeRequested'] === TRUE)) {
          if (!$this->SetAutologin($username, TRUE)) {
            warning('Could not turn on autologin');
          }
          unset($_SESSION['appRememberMeRequested']);
        } else {
          if (!$this->SetAutologin($username, FALSE)) {
            warning('Could not turn off autologin');
          }
        }
        $this->person = person(array('username' => $username), TRUE);
        if ($this->person) {
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
    }
    
    protected function changeUser($username, $password, $person) {
      if ($person) {
        $uid = $person->getUid();
        if ($uid) {
          if ($username == $person->username) {
            if ($this->SetPassword($uid, $password)) {
              $this->Authenticate($username, $password);
              if ($this->IsAuthSuccess()) {
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
            if ($this->IsAuthSuccess()) {
              $this->setLogin();
              if ($this->DeleteUser($uid)) {
                return TRUE;
              } else {
                error('Could not delete old user, but your login was changed anyway.');
                return TRUE;
              }
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
    
    protected function addUser($username, $password, $person = NULL) {
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
      $this->person = NULL;
      return TRUE;
    }

    public function action($action = NULL) {
      $action = ($action) ? $action : $_REQUEST['action'];
      switch ($action) {
        case 'login':
          if ($this->verified) {
            if ($_REQUEST['username'] && $_REQUEST['password']) {
              if ($this->login($_REQUEST['username'], $_REQUEST['password'])) {
                config::$msg = 'You have been logged in.';
                return TRUE;
              } else {
                config::$msg = 'Login failed.';
                error('Could not log you in');
              }
            } else {
              config::$msg = 'Login failed.';
              error('Could not log you in');
            }
          } else {
            config::$msg = 'Invalid nonce. Did you login to an old window? Please try again.';
            error('Invalid nonce 1, please clean cache and cookies and try again.');
          }
          return FALSE;
        break;
        case 'logout':
          config::$msg = 'You have been logged out.';
          return $this->logoff();
        break;
        case 'changeUser':
          if ($_SESSION['username'] && $_REQUEST['password'] && $_REQUEST['nonce']) {
            if ($this->verified) {
              $this->Authenticate($_SESSION['username'], $_REQUEST['password']);
              if ($this->IsAuthSuccess()) {
                if ($_REQUEST['newPassword'] == $_REQUEST['verifyNewPassword']) {
                  $person = person($_SESSION['username'], 'username');
                  if ($person) {
                    $change = $this->changeUser($_REQUEST['newUsername'], $_REQUEST['newPassword'], $person);
                    if ($change) {
                      config::$msg = 'Your username and/or password was successfully changed.';
                      return TRUE;
                    } else {
                      config::$msg = 'Could not commit the changes. Your username and passwords stay the same as before. Please try again.';
                      return FALSE;
                    }
                  } else {
                    config::$msg = 'Could not identify you. Your login has not been changed. Please try again.';
                    error('Could not identify you, please logout and login again.');
                  }
                } else {
                  config::$msg = 'The password did not match. Your login has not been changed. Please try again.';
                  error('The password did not match, please try again.');
                }
              } else {
                config::$msg = 'Could not login with the current password. Your login has not been changed. Please try again.';
                error('Could not login with your current credentials.');
              }
            } else {
              config::$msg = 'Invalid nonce. Did you use an old window? Your login has not been changed. Please try again.';
              error('Invalid nonce 2, please clean cache and cookies and try again.');
            }
          } else {
            config::$msg = 'Could not login. Your login has not been changed. Please try again.';
            error('Login failed due to missing parameters.');
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
            if ($this->verified) {
              if ($_REQUEST['person_id'] == 0) {
                $person = person('new');
                $person_id = $person->save();
              } else {
                if (isId($_REQUEST['person_id'])) {
                  $person_id = $_REQUEST['person_id'];
                } else {
                      config::$msg = 'Credential changes failed.';
                  error('Not enough parameters provided');
                  return FALSE;
                }
              }
              $person = person($person_id);
              if ($person) {
                if ($this->addUser($_REQUEST['username'], $_REQUEST['password'], $person)) {
                  $this->Authenticate($_REQUEST['username'], $_REQUEST['password']);
                  if ($this->IsAuthSuccess()) {
                    $this->setLogin();
                    return TRUE;
                  } else {
                    $this->setLogin(FALSE);
                    config::$msg = 'User created, but could not log you in. Please try logging in.';
                    error('User created, but could not log you in. Please try logging in.');
                  }
                } else {
                      config::$msg = 'Could not add the user. Please try again.';
                  error('Could not add the user');
                }
              } else {
                config::$msg = 'Could not identify you. Please try again.';
                error('Could not find person ID '.$person_id);
              }
            } else {
              config::$msg = 'Invalid nonce. Did you use an old window? Please try again.';
              error('Invalid nonce 3, please clean cache and cookies and try again.');
            }
          } else {
            config::$msg = 'Something went wrong. Please try again.';
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
        if (isObj($this->person) && isId($this->person->id)) {
          return $this->person;
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
            $form .= page::getInput(config::$login->nonce, $prefix.'nonce', 'nonce', 'hidden');
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
        $form .= (!$dialog) ? page::getButton('Login', $prefix.'login', $class, FALSE, NULL, NULL, FALSE) : '';
        $form .= page::getButton('I forgot all this!', $prefix.'reset');
        $form .= page::getFormStart($prefix.'resetForm');
          $form .= page::getInput('reset', $prefix.'action', 'action', 'hidden');
        $form .= page::getFormEnd();
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
    
    public static function getUserEdit($title = 'Change credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE, $new = FALSE, $person_id = NULL) {
      $form = page::getDivStart($prefix.(($new) ? 'new' : 'change').'UserDiv');
        $form .= page::getFormStart($prefix.(($new) ? 'new' : 'change').'UserForm');
          $form .= page::getH2($title);
          $form .= ($new) ? '' : page::getParagraph('Changing username requires changing the password too.', NULL, 'italic');
          $form .= page::getInput(config::$login->nonce, $prefix.'nonce', 'nonce', 'hidden');
          $form .= page::getInput((($new) ? 'new' : 'change').'User', $prefix.'action', 'action', 'hidden');
          $form .= ($person_id) ? page::getInput($person_id, $prefix.'person_id', 'person_id', 'hidden') : '';
          $form .= page::getDivStart($prefix.'usernameDiv');
            $form .= page::getInput((($new) ? '' : $_SESSION['username']), $prefix.(($new) ? 'u' : 'newU').'sername', (($new) ? 'u' : 'newU').'sername', 'text', 'enterSubmit', (($new) ? 'Username' : 'New username'));
          $form .= page::getDivEnd();
          $form .= ($new) ? '' : page::getDivStart($prefix.'passwordDiv');
            $form .= ($new) ? '' : page::getInput(NULL, $prefix.'password', 'password', 'password', 'enterSubmit', 'Current password');
          $form .= ($new) ? '' : page::getDivEnd();
          $form .= page::getDivStart($prefix.'newPasswordDiv');
            $form .= page::getInput(NULL, $prefix.(($new) ? 'p' : 'newP').'assword', (($new) ? 'p' : 'newP').'assword', 'password', 'enterSubmit', (($new) ? 'Password' : 'New password'));
          $form .= page::getDivEnd();
          $form .= page::getDivStart($prefix.'verifyPasswordDiv');
            $form .= page::getInput(NULL, $prefix.'verify'.(($new) ? '' : 'New').'Password', 'verify'.(($new) ? '' : 'New').'Password', 'password', 'enterSubmit', 'Verify'.(($new) ? '' : ' new').' password');
          $form .= page::getDivEnd();
          $form .= (!$dialog) ? page::getLabel('&nbsp').page::getButton((($new) ? 'Register' : 'Submit changes'), $prefix.(($new) ? 'new' : 'change').'User', $class, FALSE, NULL, NULL, FALSE) : '';
        $form .= page::getFormEnd();
      $form .= page::getDivEnd();
      if ($dialog) {
        $form .= page::getScript('
          $("#'.$prefix.(($new) ? 'new' : 'change').'UserDiv").dialog({
            autoOpen: '.(($autoopen) ? 'true' : 'false').',
            modal: true,
            width: 400,
            buttons: {
              "'.(($new) ? 'Register' : 'Submit changes').'": function() {
                if ($.trim($("#'.$prefix.(($new) ? 'u' : 'newU').'username").val()).length > 0 && $.trim($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val()).length > 0) {
                  if ($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val() == $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").val()) {
                    $("#'.$prefix.(($new) ? 'new' : 'change').'UserForm").submit();
                  } else {
                    $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster({
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
            $("#'.$prefix.(($new) ? 'new' : 'change').'UserDiv").dialog("close");
          });
        ');
      } else {
        $form .= page::getScript('
          $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster({
            theme: ".tooltipster-light",
            content: "The passwords do not match...",
            trigger: "custom",
            position: "right",
            timer: 3000
          });
          $("#'.$prefix.(($new) ? 'new' : 'change').'UserButton").click(function() {
            if ($.trim($("#'.$prefix.(($new) ? 'u' : 'newU').'sername").val()).length > 0 && $.trim($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val()).length > 0) {
              if ($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val() == $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").val()) {
                $("#'.$prefix.(($new) ? 'new' : 'change').'UserForm").submit();
              } else {
                $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("update", "The passwords do not match...").tooltipster("show");
              }
            } else {
              $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("update", "Mandatory fields are missing...").tooltipster("show");
            }
          });
        ');
      }
      $form .= page::getScript('
        $(".enterSubmit").keypress(function(e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            $("#'.$prefix.(($new) ? 'new' : 'change').'UserButton").click();
          }
        });
      ');
      return $form;
    }

    public static function getNewUser($title = 'Please choose a new username and password', $person_id, $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      return self::getUserEdit($title, $prefix, $class, $dialog, $autoopen, TRUE, $person_id);
    }
/*
      $form = page::getDivStart($prefix.'newUserDiv', $class, (($dialog) ? $title : NULL));
        $form .= ($daialog) ? '' : page::getH2($title);
        $form .= page::getFormStart($prefix.'newUserForm');
          $form .= page::getElementStart('fieldset');
            $form .= page::getInput('newUser', $prefix.'action', 'action', 'hidden');
            $form .= page::getInput($person_id, $prefix.'person_id', 'person_id', 'hidden');
            $form .= page::getInput(self::$nonce, $prefix.'nonce', 'nonce', 'hidden');
            $form .= page::getDivStart($prefix.'usernameDiv');
              $form .= page::getInput(NULL, $prefix.'usernameNew', 'username', 'text', 'enterSubmit');
            $form .= page::getDivEnd();
            $form .= page::getDivStart($prefix.'passwordDiv');
              $form .= page::getInput(NULL, $prefix.'passwordNew', 'password', 'password', 'enterSubmit');
            $form .= page::getDivEnd();
            $form .= page::getDivStart($prefix.'verifyPasswordDiv');
              $form .= page::getInput(NULL, $prefix.'verifyPasswordNew', 'verifyPassword', 'password', 'enterSubmit');
            $form .= page::getInput(NULL, $prefix.'verifyPassword', 'verifyPassword', 'password', NULL, 'Verify new password');
            $form .= page::getDivEnd();
            $form .= (!$dialog) ? page::getLabel('&nbsp').page::getButton('Register', $prefix.'register', $class, FALSE, NULL, NULL, FALSE) : '';
          $form .= page::getElementEnd('fieldset');
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
*/
  }

?>