<?php

  class tooltip extends jquery {

    public function __construct($selector = NULL, $content = NULL, array $settings = NULL) {
      parent::__construct($source);
      $defaultSettings = array(
        'theme' => '.tooltipster-light',
        'content' => 'No message defined...',
        'trigger' => 'custom',
        'position' => 'right',
        'timer' => 3000
      )
      foreach ($defaultSettings as $setting => $value) {
        $settings[$setting] = ($settings[$ßetting]) ? $settings[$setting] : $defaultSettings[$ßetting];
      }
    }
//    public function __construct($source = NULL, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

  }
  
?><?php

?>