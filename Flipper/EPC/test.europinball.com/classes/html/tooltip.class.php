<?php

  class tooltip extends jquery {
    
    public function __construct($selector = NULL, $contents = NULL, $new = TRUE, $indents = 0) {
      $new = ($new === 'update') ? FALSE : $new;
      if ($new) {
        $type = 'object';
        $props = array(
          'contentProp' => 'content',
          'theme' => '.tooltipster-light',
          'trigger' => 'custom',
          'position' => 'right',
          'timer' => 3000
        );
      } else {
        $type = 'command';
        $contents = array('update' => $contents, 'show' => FALSE);
      }
      parent::__construct($selector, 'tooltipster', $type, $contents, $props, $indents);
    }
//    jquery public function __construct($selector = NULL, $tool = NULL, $type = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    scriptCode public function __construct($source = NULL, array $params = NULL, $indents = 0) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    public function show($show = TRUE) {
      if ($show) {
        if (!in_array('show', $this->jquery['command'])) {
          $this->jquery['command'][] = 'show';
          $this->contents[] = FALSE;
        }
      } else {
        $this->jquery['command'] = array_diff($this->jquery['command'], array('show'));
        $this->contents = array_diff($this->contents, array(FALSE));
      }
      return TRUE;
    }
    
    public function hide($hide = TRUE) {
      return $this->show((bool) !$hide);
    }

  }
  
?>