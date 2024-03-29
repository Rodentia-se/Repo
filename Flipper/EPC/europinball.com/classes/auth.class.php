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
    
    public function addUser($username, $password, $person = NULL) {
      if (!preg_match('/ /', $username)) {
        if (preg_match('/^[a-zA-Z0-9\-_]+$/', $username)) {
          if (strlen($username) > 2) {
            if (strlen($username) < 33) {
              if (preg_match('/^[a-zA-Z0-9\-_]{3,32}$/', $username)) {
                if (!$this->Uid($username)) {
                  if ($this->CreateUser($username,  $password)) {
                    if ($person) {
                      if($person->setUsername($username)) {
                        return TRUE;
                      } else {
                        error('User created, but could not associate the user with the person.');
                      }
                    } else {
                      return TRUE;
                    }
                  } else {
                    config::$msg = 'Could not create user ';
                    error('Could not create user '.$username);
                  }
                } else {
                  config::$msg = $username.' is already taken. Please change to another username and try again.';
                  error($username.' is already taken. Please change to another username and try again.');
                }
              } else {
                config::$msg = 'Your username is invalid. Please use a-z, A-Z, 0-9, dash and underscore only. The username has to be at least three characters, and can not be longer than 32 characters. Change to a valid username and try again.';
                error('Your username is invalid. Please use a-z, A-Z, 0-9, dash and underscore only. The username has to be at least three characters, and can not be longer than 32 characters. Change to a valid username and try again.');
              }
            } else {
              config::$msg = 'Your username is too long. Please use no more than 32 characters and try again.';
              error('Your username is too long. Please use no more than 32 characters and try again.');
            }
          } else {
            config::$msg = 'Your username is too short. Please use at least three characters and try again.';
            error('Your username is too short. Please use at least three characters and try again.');
          }
        } else {
          config::$msg = 'You have invalid characters in your username. Please only use standard characters and try again.';
          error('You have invalid characters in your username. Please only use standard characters and try again.');
        }
      } else {
        config::$msg = 'You cannot have spaces in the username, please remove the spaces and try again.';
        error('You cannot have spaces in the username, please remove the spaces and try again.');
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
            if (!isset($_REQUEST['username']) && isset($_REQUEST['user'])) {
              $_REQUEST['username'] = $_REQUEST['user'];
            }
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
                    $uid = $this->Uid($_SESSION['username']);
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
              if ($_REQUEST['newPassword'] == $_REQUEST['verifyNewPassword']) {
                if ($_REQUEST['person_id'] == 0) {
                  $person = person('new');
                  $person_id = $person->save();
                } else {
                  if (isId($_REQUEST['person_id'])) {
                    $person_id = $_REQUEST['person_id'];
                    $uid = ($person->username) ? $this->Uid($person->username) : NULL;
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
                      if (!$_REQUEST['noLogin']) {
                        $this->setLogin();
                      }
                      if ($uid) {
                        $this->DeleteUser($uid);
                      }
                      return TRUE;
                    } else {
                      $this->setLogin(FALSE);
                      config::$msg = 'User created, but could not log you in. Please try logging in.';
                      error('User created, but could not log you in. Please try logging in.');
                    }
                  }
                } else {
                  config::$msg = 'Could not identify you. Please try again.';
                  error('Could not find person ID '.$person_id);
                }
              } else {
                config::$msg = 'The password did not match. Your login has not been changed. Please try again.';
                error('The password did not match, please try again.');
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
        case 'reset':
          if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['nonce']) && isId($_REQUEST['person_id'])) {
            if ($this->verified) {
              if ($_REQUEST['password'] == $_REQUEST['verifyPassword']) {
                if ($_REQUEST['person_id'] == 0) {
                    config::$msg = 'Credential changes failed.';
                    config::$msg = 'Could not identify you. Please try again, or contact us for assistance.';
                    return FALSE;
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
                  $uid = $this->Uid($_SESSION['username']);
                  $change = $this->changeUser($_REQUEST['username'], $_REQUEST['password'], $person);
                  if ($change) {
                    config::$msg = 'Your username and/or password was successfully changed.';
                    return TRUE;
                  } else {
                    config::$msg = 'Could not commit the changes. Your username and passwords stay the same as before. Please try again.';
                    return FALSE;
                  }
                } else {
                  config::$msg = 'Could not identify you. Please try again.';
                  error('Could not find person ID '.$person_id);
                }
              } else {
                config::$msg = 'The password did not match. Your login has not been changed. Please try again.';
                error('The password did not match, please try again.');
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
          $form .= page::getLabel(' ');
          $form .= page::getButton('Login', $prefix.'login', (($dialog) ? 'hidden ' : '').'enterButton', FALSE, NULL, NULL, FALSE);
          $form .= page::getButton('I forgot all this!', $prefix.'reset');
        $form .= page::getFormEnd();
        $form .= page::getFormStart($prefix.'resetForm', NULL, '/password-reset');
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
        $("#'.$prefix.'loginButton").click(function() {
          if ($.trim($("#'.$prefix.'username").val()).length > 0 && $.trim($("#'.$prefix.'password").val()).length > 0) {
            $("#'.$prefix.'loginForm").submit();
          }
        });
      ');
      return $form;
    }
    
    public static function getUserEdit($title = 'Change credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE, $new = FALSE, $person_id = NULL) {
      $form = page::getDivStart($prefix.(($new) ? 'new' : 'change').'UserDiv', $class, (($dialog) ? $title : NULL));
        $form .= (!$dialog) ? page::getH4($title) : '';
        $form .= page::getFormStart($prefix.(($new) ? 'new' : 'change').'UserForm');
          $form .= ($new) ? '' : page::getParagraph('Changing username requires changing the password too.', NULL, 'italic');
          $form .= page::getInput(config::$login->nonce, $prefix.'nonce', 'nonce', 'hidden');
          $form .= page::getInput((($new) ? 'new' : 'change').'User', $prefix.'action', 'action', 'hidden');
          $form .= ($person_id || $person_id == 0) ? page::getInput($person_id, $prefix.'person_id', 'person_id', 'hidden') : '';
          $form .= page::getDivStart($prefix.'usernameDiv');
            $form .= page::getInput((($new) ? '' : $_SESSION['username']), $prefix.(($new) ? 'u' : 'newU').'sername', (($new) ? 'u' : 'newU').'sername', 'text', (($dialog) ? '' : 'enterSubmit'), (($new) ? 'Username' : 'New username'));
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
          $form .= page::getLabel('&nbsp').page::getButton((($new) ? 'Register' : 'Submit changes'), $prefix.(($new) ? 'new' : 'change').'User', (($dialog) ? 'hidden ' : '').'submitButton', FALSE, NULL, NULL, FALSE);
        $form .= page::getFormEnd();
      $form .= page::getDivEnd();
      $form .= page::getScript('
        $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster({
          theme: ".tooltipster-light",
          content: "The passwords do not match...",
          trigger: "custom",
          position: "right",
          timer: 8000
        });
        $("#'.$prefix.(($new) ? 'u' : 'newU').'sername").tooltipster({
          theme: ".tooltipster-light",
          content: "Username must be at least three characters and can only include a-Z, A-Z, 0-9, dashes and underscores...",
          trigger: "custom",
          position: "right",
          timer: 8000
        });
      ');
      if ($dialog) {
        $form .= page::getScript('
          $("#'.$prefix.(($new) ? 'new' : 'change').'UserDiv").dialog({
            autoOpen: '.(($autoopen) ? 'true' : 'false').',
            modal: true,
            width: 400,
            buttons: {
              "'.(($new) ? 'Register' : 'Submit changes').'": function() {
                if ($.trim($("#'.$prefix.(($new) ? 'u' : 'newU').'sername").val()).length > 0 && $.trim($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val()).length > 0) {
                  if ($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val() == $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").val()) {
                    if ($("#'.$prefix.(($new) ? 'u' : 'newU').'sername").val().match(/^[a-zA-Z0-9\-_]{3,32}$/)) {
                      if ($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val().length > 5) {
                        $("#'.$prefix.(($new) ? 'new' : 'change').'UserForm").submit();
                      } else {
                        $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("update", "The password must be at least six characters long...").tooltipster("show");
                      }
                    } else {
                      $("#'.$prefix.(($new) ? 'u' : 'newU').'sername").tooltipster("update", "Username must be at least three characters and can only include a-Z, A-Z, 0-9, dashes and underscores...").tooltipster("show");
                    }
                  } else {
                    $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("show");
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
          $("#'.$prefix.(($new) ? 'new' : 'change').'UserButton").click(function() {
            if ($.trim($("#'.$prefix.(($new) ? 'u' : 'newU').'sername").val()).length > 0 && $.trim($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val()).length > 0) {
              if ($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val() == $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").val()) {
                if ($("#'.$prefix.(($new) ? 'u' : 'newU').'sername").val().match(/^[a-zA-Z0-9\-_]{3,32}$/)) {
                  if ($("#'.$prefix.(($new) ? 'p' : 'newP').'assword").val().length > 5) {
                    $("#'.$prefix.(($new) ? 'new' : 'change').'UserForm").submit();
                  } else {
                    $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("update", "The password must be at least six characters long...").tooltipster("show");
                  }
                } else {
                  $("#'.$prefix.(($new) ? 'u' : 'newU').'sername").tooltipster("update", "Username must be at least three characters and can only include a-Z, A-Z, 0-9, dashes and underscores...").tooltipster("show");
                }
              } else {
                $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("update", "The passwords do not match...").tooltipster("show");
              }
            } else {
              $("#'.$prefix.'verify'.(($new) ? '' : 'New').'Password").tooltipster("update", "Mandatory fields are missing...").tooltipster("show");
            }
          });
        ');
      }
      return $form;
    }
    
    function sendResetEmail($person) {
      if ($person->validate('mailAddress', $person->mailAddress)) {
        $reqNonce = ulNonce::Create('reqNonce');
        $person->setNonce($reqNonce);
        $headers = 'Content-Type: text/plain; charset=UTF-8'."\r\n".'From: '.config::$supportEmail;
        $msg = '
Hello!

You (or someone) have requested your password at europinball.org to be reset. If you are not aware of this, you can safely ignore this message.

If you want to reset your password, please click on this link or paste the address into your browser.

'.config::$baseHref.'/password-reset/?reqNonce='.urlencode($reqNonce).'

The link will expire in 10 hours, and can only be used once. This message was sent on '.date('Y-m-d H:i:s').'

If you used this function multiple times, all earlier links have been rendered invalid by this email, just as future emails will render this one invalid.

If you encounter any problems, email us at '.config::$supportEmail.' for assistance.

Regards
/EPC 2014 organizers
https://www.europinball.org/
        ';
        if (mail($person->mailAddress, 'EPC password reset', $msg, $headers)) {
          return TRUE;
        }
      }
      return FALSE;
    }

    public static function getNewUser($title = 'Please choose a new username and password', $person_id, $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      return self::getUserEdit($title, $prefix, $class, $dialog, $autoopen, TRUE, $person_id);
    }

  }

?>