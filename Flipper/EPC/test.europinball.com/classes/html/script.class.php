<?php

  class script extends html {
    
    public function __construct($source = NULL, array $params = NULL, array $settings = NULL, $indent = 0) {
      if (!$this->settings['type'] && (substr($source, -3) == '.js' || $params['src'])) {
        $this->settings['type'] = 'file';
        $source = ($source) ? $source : $params['src'];
        $this->contentParam = 'src';
        $this->inlineBlock = TRUE;
      } else {
        $this->settings['type'] = 'code';
        $this->settings['escape'] = FALSE;
      }
      $params['type'] = ($params['type']) ? $params['type'] : 'text/javascript';
      parent::__construct('script', $source, $params);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    protected function getContent($index = NULL, $string = TRUE) {
      if (is($index) && $this->settings['type'] == 'file') {
        return parent::getContent($index, $string);
      } else {
        if ($this->settings['required']) {
          $reqs = (is_array($this->settings['required'])) ? $this->settings['required'] : array($this->settings['required']);
          foreach ($reqs as $req) {
            $js = new scriptCode('
              var loaded = $("script").filter(function () {
                var src = $(this).attr("src").split("/");
                return (src[src.length - 1] == "'.$req.((substr($req, -3) != '.js') ? '.js': '').'") : true : false;
              }).length;
              if (!loaded) {
                $.getScript("'.config::$baseHref.'/js/contrib/'.$req.((substr($req, -3) != '.js') ? '.js': '').'");
              }
            ');
            $html .= $js->getHtml();
          }
        }
        $options = new BeautifierOptions();
        $options->indent_size = strlen(static::$indenter);
        $options->indent_char = substr(static::$indenter, 0, 1);
        $options->indent_level = static::$indents + 1;
        $options->max_preserve_newlines = 1;
        $jsbeautifier = new JSBeautifier();
        $content = parent::getContent($index, $string);
        return $html.ltrim($jsbeautifier->beautify($content, $options));
      }
    }
    
  }
  
?>