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
      'forms' => 'contrib/jquery.form.min.js',
      'forms' => 'contrib/jquery.form.min.js',
      'tooltipster' => 'contrib/jquery.tooltipster.js',
      'purl' => 'contrib/purl.js',
      'recaptcha' => 'contrib/recaptcha_ajax.js',
      'ga' => 'contrib/ga.js'
    );
    public $scripts = array();
    public $jquery = TRUE;
    public $jqueryui = TRUE;
    public $tooltipster = TRUE;
    public $ga = TRUE;
    public $recaptcha = FALSE;
    public $content;

    public function __construct($title='EPC', $login = TRUE, $header = NULL, $footer = NULL) {
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
            '.(($this->tooltipster) ? '<link href="'.config::$baseHref.'/css/contrib/tooltipster.css" rel="stylesheet" type="text/css" />' : '').'
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
      $person = person('login');
      $footer = self::getDivStart('clearer');
      $footer .= self::getDivEnd();
      if (!$this->loginAdded) {
        if ($this->loggedin()) {
          $footer .= self::getDivStart('logoutFooter');
          $footer .= self::getParagraph('
            <form id="footerLogoutForm" action="'.$_SERVER['REQUEST_URI'].'" method="POST">
              You are logged in as '.$person->name.'. 
              <input type="hidden" name="action" value="logout">
              <input type="button" id="footerLogoutButton" value="Log out">
            </form>', NULL, 'italic');
          $footer .= self::getScript('
            $("#footerLogoutButton").click(function() {
              $("#footerLogoutForm").submit();
            });
          ');
          $footer .= self::getDivEnd();
        } else {
          $footer .= self::getDivStart('loginFooter');
          $footer .= self::getParagraph('You are not logged in. <input type="button" id="footerLoginButton" value="Login">', NULL, 'italic');
          $footer .= self::getLogin('Please provide your login credentials', 'footer', NULL, TRUE);
          $footer .= self::getScript('
            $("#footerLoginButton").click(function() {
              $("#footerloginDiv").dialog("open");
            });
          ');
          $footer .= self::getDivEnd();
        }
      }
      $footer .= self::getScript('
        $(":button").button();
        $("input[type=submit]").button();
        $(".buttonLink").button();
        $(".buttonSubmit").click(function() {
          $("#" + this.id.replace("Button", "Form")).submit();
        });
        $("'.$prefix.'loginForm > .enterSubmit").keypress(function(e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            $("#" + this.form.id.replace("Form", "Button")).click();
          }
        });
      ');
      $footer .= self::getScript('
        $("#mainContent").tooltipster({
          theme: ".tooltipster-light",
          content: "'.config::$msg.'",
          arrow: false,
          trigger: "custom",
          timer: 10000
        })
        '.((config::$msg) ? '.tooltipster("show");' :'').'
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
    
    public function startDiv($id = NULL, $class = NULL, $title = NULL) {
      $div = self::getDivStart($id, $class, $title);
      $this->addContent($div);
      return $div;
    }
    
    public static function getDivStart($id = NULL, $class = NULL, $title = NULL) {
      return self::getElementStart('div', $id, $class, (($title) ? 'title="'.$title.'"' : ''));
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

    public function startLabel($id = NULL, $class = NULL) {
      $div = self::getLabelStart($id, $class);
      $this->addContent($div);
      return $div;
    }
    
    public static function getLabelStart($id = NULL, $class = NULL) {
      return self::getElementStart('label', $id, $class.' label');
    }
    
    public function closeLabel() {
      $div = self::getLabelEnd();
      $this->addContent($div);
      return $div;
    }

    public static function getLabelEnd() {
      return self::getElementEnd('label');
    }

    public function startForm($id = NULL, $class = NULL, $action = NULL, $method = 'POST', $ext = FALSE) {
      $form = self::getFormStart($id, $class, $action, $method, $ext);
      $this->addContent($form);
      return $form;
    }
    
    public static function getFormStart($id = NULL, $class = NULL, $action = NULL, $method = 'POST', $ext = FALSE) {
      return self::getElementStart('form', $id, $class, (($action) ? 'action="'.(($ext) ? '' : config::$baseHref.'/').$action.'" ' : 'action="'.$_SERVER['REQUEST_URI'].'" ').'method="'.$method.'"');
    }
    
    public function closeForm() {
      $form = self::getFormEnd();
      $this->addContent($form);
      return $form;
    }

    public static function getFormEnd() {
      return self::getElementEnd('form');
    }
    
    public function addForm($id = NULL, $fields = NULL, $action = NULL, $methid = 'POST', $ext = FALSE) {
      $form = self::getForm($id, $fields, $action, $methid, $ext);
      $this->addContent($form);
      return $form;
    }

    public static function getForm($id = NULL, $fields = NULL, $action = NULL, $methid = 'POST', $ext = FALSE) {
      $form = self::getFormStart($id.'Form', NULL, $action, $method, $ext);
      if (is_string($fields)) {
        $button .= self::getInput('yes', $fields, $fields, 'hidden');
      } else if (is_array($fields)) {
        foreach ($fields as $field => $value) {
          $button.= (is_int($field)) ? self::getInput('yes', $value, $value, 'hidden') : self::getInput($value, $field, $field, 'hidden');
        }
      }
    }

    public function addLabel($text, $id = NULL, $class = NULL, $close = TRUE) {
      $label = self::getLabel($text, $id, $class, $close);
      $this->addContent($label);
      return $h4;
    }
    
    public static function getLabel($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'label', $id, $class.' label', $close);
    }
    
    public static function getElementStart($type = 'p', $id = NULL, $class = NULL, $extra = NULL) {
      return '<'.$type.(($id) ? ' id="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').''.(($extra) ? ' '.$extra : '').'>';
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
      $h1 = self::getH1($text, $id, $class, $close);
      $this->addContent($h1);
      return $h1;
    }
    
    public static function getH1($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h1', $id, $class.' entry-title', $close);
    }
    
    public function addH2($text, $id = NULL, $class = NULL, $close = TRUE) {
      $h2 = self::getH2($text, $id, $class, $close);
      $this->addContent($h2);
      return $h2;
    }
    
    public static function getH2($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h2', $id, $class.' entry-title', $close);
    }
    
    public function addH3($text, $id = NULL, $class = NULL, $close = TRUE) {
      $h3 = self::getH3($text, $id, $class, $close);
      $this->addContent($h3);
      return $h3;
    }
    
    public static function getH3($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h3', $id, $class, $close);
    }
    
    public function addH4($text, $id = NULL, $class = NULL, $close = TRUE) {
      $h4 = self::getH4($text, $id, $class, $close);
      $this->addContent($h4);
      return $h4;
    }
    
    public static function getH4($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'h4', $id, $class, $close);
    }
    
    public function addSpan($text, $id = NULL, $class = NULL, $close = TRUE) {
      $span = self::getSpan($text, $id, $class, $close);
      $this->addContent($span);
      return $span;
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
      $li = self::getLi($text, $id, $class, $close);
      $this->addContent($li);
      return $li;
    }
    
    public static function getLi($text, $id = NULL, $class = NULL, $close = TRUE) {
      return self::getElement($text, 'li', $id, $class, $close);
    }

    public function addInput($value = NULL, $id = NULL, $name = NULL, $type = 'text', $class = NULL, $label = TRUE, $close = FALSE, $disabled = FALSE) {
      $input = self::getInput($value, $id, $name, $type, $class, $label, $close, $disabled);
      $this->addContent($input);
      return $input;
    }

    public static function getInput($value = NULL, $id = NULL, $name = NULL, $type = 'text', $class = NULL, $label = TRUE, $close = FALSE, $disabled = FALSE) {
      $id = ($id) ? $id : (($name) ? $name : NULL);
      $name = ($name) ? $name : (($id) ? $id : NULL);
      $label = ($type == 'hidden') ? FALSE : (($label === TRUE) ? camelCaseToSpace($name, TRUE) : $label);
      $input = ($label) ? '<label for="'.$name.'" id="'.$id.'Label" class="'.(($type == 'radio' || $type == 'checkbox') ? '' :  ' label').'">' : '';
      if ($type == 'radio' || $type == 'checkbox') {
        $input .= '<input'.(($type) ? ' type="'.$type.'"' : '').(($id) ? ' id="'.$id.'"' : '').(($name) ? ' name="'.$name.'"' : '').(($class) ? ' class="'.$class.'"' : '').(($value) ? ' checked data-previous="1"' : ' data-previous="0"').(($disabled) ? ' disabled': '').'>'.(($label) ? $label.'</label>' : '');
      } else {
        $input .= (($label) ? $label.'</label>' : '').'
          <input'.(($type) ? ' type="'.$type.'"' : '').(($id) ? ' id="'.$id.'"' : '').(($name) ? ' name="'.$name.'"' : '').(($class) ? ' class="'.$class.'"' : '').(($value || $value == 0) ? ' value="'.$value.'" data-previous="'.$value.'"' : ' data-previous=""').(($disabled) ? ' disabled': '').'>
        ';
      }
      if ($close) {
        $input .= self::getCloseIcon($id);
      }
      return $input;
    }
    
    public function addSimpleSelect($array, $id = NULL, $name = NULL, $class = NULL, $label = TRUE, $selected = NULL, $choice = FALSE) {
      $select = self::getSimpleSelect($array, $id, $name, $class, $label, $selected, $choice);
      $this->addContent($select);
      return $select;
    }

    public static function getSimpleSelect($array, $id = NULL, $name = NULL, $class = NULL, $label = TRUE, $selected = NULL, $choice = FALSE) {
      if (is_object($array)) {
        $array = (array) $array;
      }
      if (is_array($array) && count($array) > 0) {
        $id = ($id) ? $id : (($name) ? $name : NULL);
        $name = ($name) ? $name : (($id) ? $id : NULL);
        $label = ($label === TRUE) ? camelCaseToSpace($name, TRUE) : $label;
        $select = ($label) ? '<label'.(($id) ? ' for="'.$id.'" id="'.$id.'Label"' : '').' class="label">'.$label.'</label> ' : '';
        $select .= '<select'.(($id) ? ' id="'.$id.'" name="'.$id.'"' : '').(($class) ? ' class="'.$class.'"' : '').' data-previous="'.$selectedId.'">';
        $select .= ($choice) ? '<option value="0">'.(($choice === TRUE) ? 'Choose '.camelCaseToSpace($name) : $choice).'</option>' : '';
        foreach ($array as $value => $text) {
          $select .= '<option value="'.$value.'"'.(($value == $selected || $text == $selected) ? ' selected' : '').'>'.$text.'</option>';
        }
        $select .= '</select>';
        return $select;
      } else {
        return FALSE;
      }
    }

    public function addSelect($objs = NULL, $id = NULL, $class = NULL, $label = TRUE, $selected = NULL, $add = FALSE) {
      $select = self::getSelect($objs, $id, $class, $label, $selected, $add);
      $this->addContent($select);
      return $select;
    }

    public static function getSelect($objs, $id = NULL, $class = NULL, $label = TRUE, $selected = NULL, $add = FALSE) {
      if (isGroup($objs)) {
        $select = $objs->getSelect($id, $class, $label, $selected, $add);
      } else {
        $select = group::_getSelect($id, $class, $label, $selected, $add, $objs);
      }
      return $select;
    }
    
    public function addCloseIcon($id = NULL, $class = NULL, $label = NULL) {
      $icon = self::getCloseIcon($id, $class, $label);
      $this->addContent($icon);
      return $icon;
    }
    
    public static function getCloseIcon($id = NULL, $class = NULL, $label = NULL) {
      return self::getIcon(config::$baseHref.'images/cancel.png', 'close_'.$id, 'closeIcon editIcon '.$class, 'Click to remove '.$label);
    }

    public function addAddIcon($id = NULL, $class = NULL, $label = NULL) {
      $icon = self::getAddIcon($id, $class, $label);
      $this->addContent($icon);
      return $icon;
    }
    
    public static function getAddIcon($id = NULL, $class = NULL, $label = NULL) {
      return self::getIcon(config::$baseHref.'images/add_icon.gif', 'add_'.$id, 'addIcon editIcon '.$class, 'Click to add '.$label);
    }

    public function addIcon($url, $id = NULL, $class = NULL, $label = NULL) {
      $icon = self::getIcon($url, $id, $class, $label);
      $this->addContent($icon);
      return $icon;
    }
    
    public static function getIcon($url, $id = NULL, $class = NULL, $label = NULL) {
      $url = (preg_match('/^http/', $url)) ? $url : config::$baseHref.'/'.$url;
      return '<img'.(($id) ? ' id="'.$id.'"' : '').' src="'.$url.'" class="icon'.(($class) ? ' '.$class : '').'"'.(($label) ? ' alt="'.$label.'" title="'.$label.'"' : '').'>';
    }
    
    public function addClickButton($text = 'submit', $id = NULL, $class = NULL, $forms = TRUE, $action = NULL, $ext = NULL, $script = NULL, $method = 'POST', $header = NULL, $label = FALSE) {
      return $this->addButton($text, $id, $class, $forms, $action, $ext, $script, $method, $header, $label);
    }
    
    public static function getClickButton($text = 'submit', $id = NULL, $class = NULL, $forms = TRUE, $action = NULL, $ext = NULL, $script = NULL, $method = 'POST', $header = NULL, $label = FALSE) {
      return self::getButton($text, $id, $class, $forms, $action, $ext, $script, $method, $header, $label);
    }

    public function addButton($text = 'submit', $id = NULL, $class = NULL, $forms = FALSE, $action = NULL, $ext = NULL, $script = NULL, $method = 'POST', $header = NULL, $label = FALSE) {
      $button = self::getButton($text, $id, $class, $forms, $action, $ext, $script, $method, $header, $label);
      $this->addContent($button);
      return $button;
    }
    
    public static function getButton($text = 'submit', $id = NULL, $class = NULL, $forms = FALSE, $action = NULL, $ext = NULL, $script = NULL, $method = 'POST', $header = NULL, $label = FALSE) {
      $id = ($id) ? $id : preg_replace('/[^A-Za-z0-9]/', '', $text);
      $action = ($action) ? (($ext) ? '' : config::$baseHref.'/').$action : $_SERVER['REQUEST_URI']; 
      $button = ($forms) ? self::getFormStart($id.'Form', NULL, $action, $method, $ext) : '';
      $button .= ($header) ? $header : '';
      $button .= self::getInput($text, $id.'Button', $id.'Button', 'button', (($script !== FALSE) ? 'buttonSubmit ' : '').$class, $label);
      if (is_string($forms)) {
        $button .= self::getInput('yes', $form, $form, 'hidden');
      } else if (is_array($forms)) {
        foreach ($forms as $form => $value) {
          $button.= (is_int($form)) ? self::getInput('yes', $value, $value, 'hidden') : self::getInput($value, $form, $form, 'hidden');
        }
      }
      $button .= ($forms) ? '</form>' : '';
      return $button;
    }
    
    public function checkLogin($action = TRUE, $add = FALSE, $req = FALSE, $title = 'Please provide your login credentials') {
      if ($this->loggedin()) {
        return ($add) ? NULL : TRUE;
/*
      } else if ($action && $_REQUEST['action'] == 'login' && config::$login->action('login')) {
        return ($add) ? NULL : TRUE;
*/
      } else {
        $login = self::getLogin($title);
        if ($add || $req) {
          $this->addContent($login);
          $this->loginAdded = TRUE;
        } 
        return ($add) ? $login : FALSE;
      }
    }

    public function reqLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL) {
      if ($this->loggedin()) {
        // || ($_REQUEST['action'] == 'login' && config::$login->action('login'))) {
        return TRUE;
      } else {
        $this->addLogin($title, $prefix, $class, FALSE);
        return FALSE;
      }
    }
    
    public function addLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL, $dialog = FALSE) {
      $login = self::getLogin($title, $prefix = NULL, $class = NULL, $dialog = FALSE);
      $this->addContent($login);
      return $login;
    }

    public static function getLogin($title = 'Please provide your login credentials', $prefix = NULL, $class = NULL, $dialog = FALSE) {
      return auth::getLogin($title, $prefix, $class, $dialog);
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
    
    public function addPhotoEdit($obj, $prefix = NULL, $class = NULL) {
      $photo = self::getPhotoEdit($obj, $prefix, $class);
      $this->addContent($photo);
      return $photo;
    }

    
    public static function getPhotoEdit($obj, $prefix = NULL, $class = NULL) {
      return $obj->getPhotoEdit($prefix, $class);
    }
    
    public function addUserEdit($title = 'Change credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      $form = self::getUserEdit($title, $prefix, $class, $dialog, $autoopen);
      $this->addContent($form);
      return $form;
    }

    public static function getUserEdit($title = 'Change credentials', $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      return auth::getUserEdit($title, $prefix, $class, $dialog, $autoopen);
    }

    public function submit($header = TRUE, $footer = TRUE, $div = TRUE) {
      echo $this->getContent($header, $footer, $div);
    }

    public function loggedin() {
      return config::$login->loggedin();
    }

    public function addNewUser($title = 'Please provide your login credentials', $person_id, $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      $dialog = self::getNewUser($title, $person_id, $prefix, $class, $dialog, $autoopen);
      $this->addContent($dialog);
      return $dialog;
    }

    public static function getNewUser($title = 'Please choose a new username and password', $person_id, $prefix = NULL, $class = NULL, $dialog = FALSE, $autoopen = FALSE) {
      return auth::getNewUser($title, $person_id, $prefix, $class, $dialog, $autoopen);
    }

  }

?>