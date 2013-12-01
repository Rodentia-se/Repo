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
    
    public function setEditable($editable = TRUE) {
      $this->js_jquery = $editable;
      $this->js_jqueryui = $editable;
      $this->js_datatables = $editable;
      $this->js_jeditable = $editable;
    }
   
    public function addScript($script, $onload = TRUE) {
      $script = self::getScript($script, $onload);
      $this->addContent($script);
      return $script;
    }
    
    public static function getScript($script, $onload = TRUE) {
      return '
        <script type="text/javascript">
          '.(($onload) ? '$(document).ready(function() {' : '').'
            '.$script.'
          '.(($onload) ? '});' : '').'
        </script>
      ';
    }
    
    public function focus($id) {
      $this->addScript('$("#'.$id.'").focus()', TRUE);
    }
    
    public function startDiv($id, $class = NULL) {
      $div = getElementStart('div', $id, $class);
      $this->addContent($div);
      return $div;
    }
    
    public function closeDiv() {
      $div = getElementEndt('div');
      $this->addContent($div);
      return $div;
    }
    
    public static function getElementStart($type = 'p', $id = NULL, $class = NULL) {
      return '<'.$type.(($id) ? ' id="'.$id.'"' : '').(($class) = ' class="'.$class.'"' : '').'>';
    } 

    public static function getElementEnd($type = 'p') {
      return '</'.$type.'>';
    } 

    public function addElement($text, $type = 'p', $id = NULL, $class = NULL, $close = TRUE) {
      $element = self::getElement($text, $type, $class, $close);
      $this->addContent($element);
      return $element;
    }
    
    public static function getElement($text, $type = 'p', $id = NULL, $class = NULL, $close = TRUE) {
      if ($type) {
        $element = self::getElementStart($type, $id, $class);
      } else if ($class) {
        $element = self::getElementEnd('span', $id, $class);
      }
      $element .= $text;
      $element .= ($close) ? self::closeElement($type) : '';
      return $element;
    }
    
    public function addParagraph($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getParagraph($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getParagraph($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'p', $id, $class, $close);
    }
    
    public function addH1($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getH1($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getH1($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h1', $id, $class.' entry-title', $close);
    }
    
    public function addH2($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getH2($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getH2($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h2', $id, $class.' entry-title', $close);
    }
    
    public function addH3($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getH3($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getH3($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h3', $id, $class, $close);
    }
    
    public function addH4($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getH4($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getH4($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h4', $id, $class, $close);
    }
    
    public function addSpan($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getSpan($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getSpan($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'span', $id, $class, $close);
    }
    
    public function addTable($id, $headers = array('Name'), $rows = NULL, $class = NULL) {
      $table = self::getTable($id, $headers, $rows, $class);
       $this->addContent($table);
     return $table;
    }
   
    public static function getTable($id, $headers = array('Name'), $rows = NULL, $class = NULL) {
      $table = '
        <table id="resultsTable" class="'.$class.'">
          <thead>
            <tr>
      ';
      foreach ($headers as $header) {
        $table .= '<th>'.$header."</th>\n";
      }
      $table .= '
            </tr>
          </thead>
          <tbody>
      ';
      if ($rows) {
        foreach ($rows as $row => $cells) {
          $table .= '<tr>';
          if ($cells) {
            if (count($cells) != count($headers)) {
              warning('¨Headers and cells count does not match - no cells added');
            }
            $cell = 0;
            foreach ($headers as $header) {
              if ($cells[$header]) {
                $table .= '<td>'.$cells[$header]."</td>\n";
              } else {
                $table .= '<td>'.$cells[$cell]."</td>\n";
              }
              $cell++;
            }
          }
          $table .= "</tr>\n";
        }
      }
      $table .= '
          </tbody>
        </table>
      ';
      return $table;
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

    public function checkLogin($action = TRUE, $add = FALSE, $req = FALSE, $title = 'Please provide your login credentials') {
      if ($this->loggedin()) {
        return ($add) ? NULL : TRUE;
      } else if ($action && $_REQUEST['action'] == 'login' && $this->login->action('login')) {
        return ($add) ? NULL : TRUE;
      } else {
        $login = $this->getLogin($title);
        $this->addContent((($add || $req) ? $login : ''));
        return ($add) ? $login : FALSE;
      }
    }

    public function reqLogin($title = 'Please provide your login credentials', $action = TRUE) {
      return $this->checkLogin($action, FALSE, TRUE, $title);
    }
    
    public function addLogin($title = 'Please provide your login credentials', $action = TRUE) {
      return $this->checkLogin($action, TRUE, FALSE, $title);
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