<?php

  require_once(config::$baseDir.'/contrib/jsbeautifier.php');
  
  class scriptCode extends script {
    
    public function __construct($code = NULL, array $params = NULL) {
      $params['type'] = ($params['type') ? $params['type'] : 'text/javascript';
      parent::__construct($code, $params);
      $this->block = TRUE;
      $this->selfClose = FALSE;
      $this->settings['type'] == 'code';
      $this->settings['escape'] == TRUE;
    }
//    public function __construct($code = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    protected static function contentToHtml($content, $escape = FALSE) {
      if (is_array($content)) {
        foreach ($content as $part) {
          $html .= static::contentToHtml($part, $escape);
        }
      } else {
        $html = ($escape) ? htmlspecialchars($content) : $contect;
      }
      $options = new BeautifierOptions();
      $options->indent_size = strlen(static::$indenter);
      $options->indent_char = substr(static::$indenter, 0, 1);
      $options->indent_level = static::$indents;
      $options->max_preserve_newlines = 1;
      $jsbeautifier = new JSBeautifier();
      return $jsbeautifier->beautify($html, $options);
    }
  }

?>