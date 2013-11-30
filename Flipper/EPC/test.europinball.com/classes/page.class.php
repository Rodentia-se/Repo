<?php

  class page {
    
    public static $_login;
    public $content;

    public function __construct($title='EPC', $login = FALSE, $header = NULL, $footer = NULL) {
      if ($login) {
        if (!self::$_login) {
//          self::$_login = new auth(NULL, NULL, config::$loginBackend);
          self::$_login = new auth();
        } else {
          if ($this->loggedin() && !$this->login->person) {
            $this->login->person = $this->login->getPerson();
            if ($this->login->person) {
              $this->login->person_id = $this->login->person->id;
            }
          }
        }
        $this->login = self::$_login;
      }
      $this->title = $title;
      if ($header) {
        $this->addHeader($header);
      }
      if ($footer) {
        $this->addFooter($footer);
      }
    }

    public function addHeader($title = NULL, $header = FALSE) {
      $title = ($title) ? $title : $thie->title;
      $this->header = ($header && $header !== TRUE) ? $header : $this->getHeader($title);
    }
    
    public function getHeader($title = NULL) {
      $title = ($title) ? $title : $thie->title;
      return '
        <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            '.(($this->js_jquery) ? '<script type="text/javascript" src="'.config::$baseHref.'js/contrib/jquery.js"></script>' : '').'
            '.(($this->js_jqueryui) ? '<script type="text/javascript" src="'.config::$baseHref.'/js/contrib/jquery-ui.js"></script>' : '').'
            '.(($this->js_datatables) ? '<script type="text/javascript" src="'.config::$baseHref.'/js/contrib/jquery.dataTables.min.js"></script>' : '').'
            '.(($this->js_jeditable) ? '<script type="text/javascript" src="'.config::$baseHref.'/js/contrib/jquery.jeditable.mini.js"></script>' : '').'
            '.(($this->js_datatables && $this->js_jeditable) ? '<script type="text/javascript" src="'.config::$baseHref.'/js/contrib/jquery.dataTables.editable.js"></script>' : '').'
            '.(($this->js_purl) ? '<script type="text/javascript" src="'.config::$baseHref.'/js/contrib/purl.js"></script>' : '').'
            '.(($this->js_recaptcha) ? '<script type="text/javascript" src="'.config::$baseHref.'/js/contrib/recaptcha_ajax.js"></script>' : '').'
            <script type="text/javascript" src="'.config::$baseHref.'/js/general.js"></script>
            '.(($this->js_datatables) ? '<link href="'.config::$baseHref.'/css/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css" />' : '').'
            '.(($this->js_jqueryui) ? '<link href="'.config::$baseHref.'/css/jquery-ui.css" rel="stylesheet" type="text/css" />' : '').'
            <link href="'.config::$baseHref.'/css/epc.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="'.config::$baseHref.'/js/contrib/ga.js"></script>
            <link rel="shortcut icon" href="'.config::$baseHref.'/images/favicon.ico" type="image/x-icon" />
            <title>'.$title.'</title>
          </head>
          <body>
      ';
    }

    public function addFooter($footer = FALSE) {
      $this->footer = ($footer && $footer !== TRUE) ? $footer : $this->getFooter();
    }

    public function getFooter() {
      return '
            <div id="loginbuttons">
              '.(($this->loggedin()) ? '
                <p class="italic">You are logged in as '.$this->login->person->name.'. <a href="'.config::$baseHref.'/login/?action=logout"><input type="button" id="logoutButton" value="Log out"></a>' :
                '<p class="italic">You are not logged in. <a href="'.config::$baseHref.'/login"><input type="button" id="loginButton" value="Log in"></a').'
          </body>
        </html>
      ';
    }

    public function reqLogin($title = 'Please provide your login credentials', $action = TRUE) {
      if ($this->loggedin()) {
        return TRUE;
      } else if ($action && $_REQUEST['action'] == 'login' && $this->login->action('login')) {
        return TRUE;
      } else {
        $this->content .= $this->getLogin($title);
        return FALSE;
      }
    }
    
    public function addLogin($title = 'Please provide your login credentials', $action = TRUE) {
      if ($this->loggedin()) {
        return NULL;
      } else if ($action && $_REQUEST['action'] == 'login' && $this->login->action('login')) {
        return NULL;
      } else {
        $this->content .= $this->getLogin($title);
        return $this->getLogin($title);
      }
    }

    public function addContent($content) {
      $this->content .= $content;
    }
    
    public function getContent($header = TRUE, $footer = TRUE, $div = TRUE) {
      $this->content = ($div) ? '<div id="mainContent" class="content">'.$this->content.'</div>' : $this->content;
      if ($header && !$this->header) {
        $this->addHeader($header);
      }
      if ($footer && !$this->footer) {
        $this->addFooter($footer);
      }
      return (($header) ? $this->header : '').$this->content.(($footer) ? $this->footer : '');
    }
    
    public function submit($header = TRUE, $footer = TRUE, $div = TRUE) {
      echo $this->getContent($header, $footer, $div);
    }

    public function getAuthPerson() {
      return $this->login->getPerson();
    }
    
    public function login($username, $password, $nonce) {
      return $this->login->login($username, $password, $nonce);
    }
    
    public function logoff() {
      return $this->login->logoff();
    }

    public function loggedin() {
      return $this->login->loggedin();
    }

    public function getLogin($title = 'Please provide your login credentials') {
      return $this->login->getLogin($title);
    }

  }

?>