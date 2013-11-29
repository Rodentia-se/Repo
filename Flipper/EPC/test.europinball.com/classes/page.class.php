<?php

  class page {
    
    public static $_login;

    public function __construct($title='EPC', $login = FALSE) {
      if ($login) {
        if (!self::$_login) {
//          self::$_login = new auth(NULL, NULL, config::$loginBackend);
          self::$_login = new auth();
        } 
        $this->login = self::$_login;
      }
    }

    public function addHeader($title = 'EPC') {
      $this->header = $this->getHeader($title);
    }
    
    public function getHeader($title = 'EPC') {
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
      ';
    }

    public function reqLogin($title = 'Please provide your login credentials', $action = TRUE) {
      preDump($this->login);
      if ($this->login->checkLogin()) {
        return TRUE;
      } else if ($action && $this->login->action('login')) {
        return TRUE;
      } else {
        $this->content .= $this->login->getLogin($title);
      }
    }
    
    public function addContent($content) {
      $this->content .= $content;
    }
    
    public function getContent($header = TRUE, $footer = TRUE) {
      return (($header) ? $this->header : '').$this->content.(($this->footer) ? $this->footer : '');
    }
    
    public function submit($header = TRUE, $footer = TRUE) {
      echo $this->getContent($header, $footer);
    }

  }

?>