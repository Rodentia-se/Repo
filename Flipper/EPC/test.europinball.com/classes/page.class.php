<?php

  class page {
    
    public static $_login;
    public static $_scripts = array(
      'jquery' => 'contrib/jquery.js',
      'jqueryui' => 'contrib/jquery-ui.js',
      'datatables' => 'contrib/jquery.dataTables.js',
      'datatablesReload' => 'contrib/jquery.dataTables.reload.js',
      'jeditable' => 'contrib/jquery.jeditable.js',
      'autocomplete' => 'contrib/autocomplete.js',
      'datatablesEditable' => 'contrib/jquery.dataTables.editable.js',
      'combobox' => 'contrib/jquery.combobox.js',
      'purl' => 'contrib/purl.js',
      'recaptcha' => 'contrib/recaptcha_ajax.js',
      'ga' => 'contrib/ga.js'
    );
    public $scripts = array();
    public $jquery = TRUE;
    public $jqueryui = TRUE;
    public $datatables = FALSE;
    public $datatablesReload = FALSE;
    public $jeditable = FALSE;
    public $purl = TRUE;
    public $ga = TRUE;
    public $recaptcha = FALSE;
    public $content;

    public function __construct($title='EPC', $login = TRUE, $header = NULL, $footer = NULL) {
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

    public function addHeader($title = NULL, $src = FALSE) {
      $title = ($title) ? $title : $thie->title;
      $this->header = ($src && $src !== TRUE) ? $src : $this->getHeader($title);
      return $this->header;
    }
    
    public function getHeader($title = NULL, $scripts = NULL) {
      $title = ($title) ? $title : $thie->title;
      $header = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      ';
      $header .= $this->addScripts($scripts);
      $header .= '
            '.(($this->jqueryui) ? '<link href="'.config::$baseHref.'/css/contrib/aristo.css" rel="stylesheet" type="text/css" />' : '').'
            '.(($this->datatables) ? '<link href="'.config::$baseHref.'/css/contrib/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css" />' : '').'
            '.(($this->combobox) ? '<link href="'.config::$baseHref.'/css/contrib/jquery.combobox.css" rel="stylesheet" type="text/css" />' : '').'
            <link href="'.config::$baseHref.'/css/epc.css" rel="stylesheet" type="text/css" />
            <link rel="shortcut icon" href="'.config::$baseHref.'/images/favicon.ico" type="image/x-icon" />
            <title>'.$title.'</title>
          </head>
          <body>
      ';
      return $header;
    }
    
    public function addFooter($footer = FALSE) {
      $this->footer = ($footer && $footer !== TRUE) ? $footer : $this->getFooter();
      return $this->footer;
    }

    public function getFooter() {
      if (!$this->loginAdded) {
        $footer = self::getDivStart('loginFooter');
          if ($this->loggedin()) {
            $footer .= self::getParagraph('
              <form id="footerLogoutForm" action="'.$_SERVER['REQUEST_URI'].'" method="POST">
                You are logged in as '.$this->login->person->name.'. 
                <input type="hidden" name="action" value="logout">
                <input type="button" id="footerLogoutButton" value="Log out">
              </form>', NULL, 'italic');
            $footer .= self::getScript('
              $("#footerLogoutButton").click(function() {
                $("#footerLogoutForm").submit();
              });
            ');
          } else {
            $footer .= self::getParagraph('You are not logged in. <input type="button" id="footerLoginButton" value="Log in">', NULL, 'italic');
            $footer .= self::getLogin('Please provide your login credentials', 'footer', NULL, TRUE);
            $footer .= self::getScript('
              $("#footerLoginButton").click(function() {
                $("#footerloginDiv").dialog("open");
              });
            ');
        }
        $footer .= self::getDivEnd();
      }
      $footer .= self::getScript('
        $(":button").button();
        $("input[type=submit]").button();
      ');
      $footer .= '
          </body>
        </html>
      ';
      return $footer;
    }

    public function setEditable($editable = TRUE) {
      $this->jquery = $editable;
      $this->jqueryui = $editable;
      $this->datatables = $editable;
      $this->jeditable = $editable;
      $this->autocomplete = $editable;
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
    
    public function addScripts($scripts = NULL, $default = TRUE) {
      if (is_string($scripts)) {
        if (!in_array($scripts, $this->scripts)) {
          $this->scripts[] = $scripts;
        }
      } else if (is_array($scripts)) {
        foreach ($scripts as $script) {
          if (!in_array($script, $this->scripts)) {
            $this->scripts[] = $script;
          }
        }
      }
      $scripts = self::getScripts($this->scripts, $default, TRUE);
      $this->scripts = $scripts['scripts'];
      return $scripts['htmlCode'];
    }
    
    public function getScripts($scripts = NULL, $default = TRUE, $array = FALSE) {
      $scriptSrcs = array();
      if ($default) {
        $this->datatablesEditable = ($this->datatables && $this->jeditable) ? TRUE : FALSE;
        foreach (self::$_scripts as $script => $scriptSrc) {
          if ($this->$script && !in_array($scriptSrc, $scriptSrcs)) {
            $scriptSrcs[] = $scriptSrc;
          }
        }
      }
      if (is_string($scripts)) {
        if (!in_array($scripts, $scriptSrcs)) {
          $scriptSrcs[] = $scripts;
        }
      } else if (is_array($scripts)) {
        foreach ($scripts as $script) {
          if (!in_array($script, $scriptSrcs)) {
            $scriptSrcs[] = $script;
          }
        }
      }
      $scriptSrcs[] = 'general.js';
      foreach ($scriptSrcs as $script) {
        $htmlCode .= '<script type="text/javascript" src="'.config::$baseHref.'js/'.$script.'"></script>'."\n";
      }
      return ($array) ? array('scripts' => $scriptSrcs, 'htmlCode' => $htmlCode) : $htmlCode;
    }

    public function focus($id) {
      $this->addScript('$("#'.$id.'").focus()', TRUE);
    }
    
    public function startDiv($id = NULL, $class = NULL) {
      $div = self::getDivStart($id, $class);
      $this->addContent($div);
      return $div;
    }
    
    public static function getDivStart($id = NULL, $class = NULL) {
      return self::getElementStart('div', $id, $class);
    }
    
    public function closeDiv() {
      $div = self::getDivEnd();
      $this->addContent($div);
      return $div;
    }

    public static function getDivEnd() {
      return self::getElementEnd('div');
    }

    public function startUl($id = NULL, $class = NULL) {
      $div = self::getUlStart($id, $class);
      $this->addContent($div);
      return $div;
    }
    
    public static function getUlStart($id = NULL, $class = NULL) {
      return self::getElementStart('ul', $id, $class);
    }
    
    public function closeUl() {
      $div = self::getUlEnd();
      $this->addContent($div);
      return $div;
    }

    public static function getUlEnd() {
      return self::getElementEnd('ul');
    }

    public static function getElementStart($type = 'p', $id = NULL, $class = NULL) {
      return '<'.$type.(($id) ? ' id="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').'>';
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
        $element = self::getElementStart('span', $id, $class);
      }
      $element .= $text;
      $element .= ($close) ? self::getElementEnd($type) : '';
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
    
    public function addTable($id = NULL, $headers = array('Name'), $rows = NULL, $class = NULL) {
      $table = self::getTable($id, $headers, $rows, $class);
       $this->addContent($table);
     return $table;
    }
   
    public static function getTable($id = NULL, $headers = array('Name'), $rows = NULL, $class = NULL) {
      $table = '
        <table'.(($id) ? ' id="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').'>
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
              warning('Headers and cells count does not match - not all cells added');
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

    public function addLi($text, $id = NULL, $class = NULL, $close = TRUE) {
      $paragraph = self::getLi($text, $id, $class, $close);
      $this->addContent($paragraph);
      return $paragraph;
    }
    
    public static function getLi($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'li', $id, $class, $close);
    }

    public function addInput($value = NULL, $id = NULL, $class = NULL, $type = 'text', $label = TRUE) {
      $input = self::getInput($value, $id, $class, $type, $label);
      $this->addContent($input);
      return $input;
    }

    public static function getInput($value = NULL, $id = NULL, $class = NULL, $type = 'text', $label = TRUE) {
      $input = ($label) ? '<label'.(($id) ? ' for="'.$id.'" id="'.$id.'Label"' : '').' class="'.(($class) ? $class.'Label ' : '').'label">'.$label : '';
      if ($type == 'radio' || type == 'checkbox') {
        $input .= '<input'.(($type) ? ' type="'.$type.'"' : '').(($id) ? ' id="'.$id.'" name="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').(($value) ? ' checked' : '').'>'.(($label) ? '</label>' : '');
      } else {
        $input .= (($label) ? '</label>' : '').'
          <input'.(($type) ? ' type="'.$type.'"' : '').(($id) ? ' id="'.$id.'" name="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').(($value) ? ' value="'.$value.'"' : '').'>';
      }
      return $input;
    }

    public function checkLogin($action = TRUE, $add = FALSE, $req = FALSE, $title = 'Please provide your login credentials') {
      if ($this->loggedin()) {
        return ($add) ? NULL : TRUE;
      } else if ($action && $_REQUEST['action'] == 'login' && $this->login->action('login')) {
        return ($add) ? NULL : TRUE;
      } else {
        $login = self::getLogin($title);
        if ($add || $req) {
          $this->addContent($login);
          $this->loginAdded = TRUE;
        } 
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

    public static function getLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL, $closeButton = FALSE) {
      return auth::getLogin($title, $prefix, $class, $closeButton);
    }

    public function addNewUser($title = 'Please provide your login credentials', $person_id) {
      $dialog = self::getNewUser($title, $person_id);
      $this->addContent($dialog);
      return $dialog;
    }

    public static function getNewUser($title = 'Please choose a new username and password', $person_id, $prefix = NULL, $class = NULL, $closeButton = FALSE) {
      return auth::getNewUser($title, $person_id, $prefix, $class, $closeButton);
    }

  }

?>