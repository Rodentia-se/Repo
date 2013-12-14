<?php

  class clickButton extends button {
    
    protected $form;
    protected $script;
    
    public function __construct($value = 'submit', $name = NULL, $url = NULL, $form = TRUE, $script = TRUE, array $params = NULL) {
      $params['name'] = ($name) ? $name : $value;
      $params['id'] = ($params['id']) ? $params['id'] : preg_replace('/[^a-zA-Z0-9]/', '', $params['name']);
      if ($form === TRUE) {
        $this->form = new form($params['id'].'Form', $url);
      } else if (is($form)) {
        $this->form = (isHtml($form)) ? $form : new form($form);
      }
      if ($params['id'] && $this->form && $this->form->id) {
        $this->form->inline = TRUE;
        $this->form->hide();
      }
      if ($script == TRUE) {
        $this->script = new click('#'.$params['id'], '$("#'.$this->form->id.'").submit();', static::$indents);
      } else if (is($script)) {
        $this->script = (isHtml($script)) ? $script : new click($script);
      }
      $this->inline = true;
      $this->settings['insideForm'] = TRUE;
      parent::__construct($value, $name, $params);
    }
//    public function __construct($selector = NULL, $code = NULL, $indents = 0) {
//    form public function __construct($id = NULL, $action = NULL, $method = 'POST', array $params = NULL) {
//    jquery public function __construct($selector = NULL, $tool = NULL, $type = NULL, $contents = NULL, array $props = NULL, $indents = 0) {
//    button public function __construct($value = 'submit', $name = NULL, array $params = NULL) {
//    input public function __construct($name = NULL, $value = NULL, $type = 'text', $label = TRUE, array $params = NULL) {
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {

    public function getHtml($form = TRUE, $button = TRUE, $script = TRUE) {
      if ($button) {
        if ($form && $this->form) {
          if ($this->insideForm) {
            $this->form->addContent($this, $this);
            return ($script) ? $this->form->getHtml().$this->script->getHtml() : $this->form->getHtml();
          } else {
            return ($script) ? $this->form->getHtml().parent::getHtml().$this->script->getHtml() : $this->form->getHtml().parent::getHtml();
          }
        } else {
          return parent::getHtml();
        }
      } else {
        return ($form && $this->form) ? (($this->script) ? $this->form->getHtml().$this->script->getHtml() : $this->form->getHtml()) : NULL;
      }
    }
    
  }
  
?>