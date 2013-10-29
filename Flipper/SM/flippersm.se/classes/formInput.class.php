<?php
  require_once('element.class.php'); // Why is this needed? Shouldn't autload take care of that?

  class formInput extends element {
    
    public $label;
    public $labels = true;
    public $table = false;
    public $value;
    public $text;
    public $keydown;
    public $keyup;
    public $keypress;
    public $blur;
    public $input;
    public $disabled = false;
    public $loading = true;
    public $addIcon = true;
    public $addAction;
    public $addName;
    public $cancelAction;
    public $options = array();
    public $type = 'text';
    public $selectedValue;
    public $class = 'formInput';
    
    function __construct($type = 'text', $name = 'unnamed', $value = null) {
      $this->type = $type;
      $this->name = $name;
      $this->id = $name;
      $this->value = $value;
    }
    
    public function update() {
      foreach($this->options as $option) {
        if ($this->selectedValue && $option->value == $this->selectedValue) {
          $option->selected = true;
        }
        $option->update();
      }
      $this->updateContent();
    }
    
    function updateContent() {
      $this->content = '';
      if ($this->labels) {
        if ($this->label) {         
          $this->content .= '<label for="'.$this->id.'" id="'.$this->id.'Label" class="'.$this->class.'">'.$this->label.': </label>';
        }
        if ($this->table) { 
          $this->content .= "</td>\n<td>"; 
        } else { 
          $this->content .= "\n"; 
        }
      }
      switch ($this->type) {
        case 'select':
          $this->content .= '<select name="'.$this->name.'" id="'.$this->id.'" class="'.$this->class.'"';
          if ($this->action) {
            $this->content .= ' onchange="'.$this->action.'"';
          }
          if ($this->disabled) {
            $this->content .= ' disabled';
          }
          $this->content .= ">\n";
          foreach($this->options as $option) {
            $this->content .= $option->content;
          }
          $this->content .= "</select>\n";
          if ($this->loading) {
            $this->content .= '<img id="'.$this->name.'Loading" src="images/sel-loader.gif" />'."\n";
          }
          if ($this->addIcon) {
            $this->content .= '<img id="'.$this->name.'Add" src="images/add_icon.gif" class="icon"';
            if ($this->addAction) {
              $this->content .= ' onclick="'.$this->addAction.'"';
            }
            $this->content .= ' alt="Click to add a new '.$this->name.'" title="CLick to add a new '.$this->name.'"/>'."\n";
            $addText = new formInput();
            $addText->id = $this->name.'AddText';
            $addText->name = $addText->id;
            $addText->labels = false;
            $addText->table = false;
            $addText->type = 'text';
            $addText->class = 'invisible';
            $this->content .= $addText->output();
            $this->content .= '<img id="'.$this->name.'AddCancel" src="images/cancel.png" class="'.$addText->class.' icon"';
            if ($this->cancelAction) {
              $this->content .= ' onclick="'.$this->cancelAction.'"';
            }
            $this->content .= ' alt="Click to cancel" title="CLick to cancel"/>';
          }
          if ($this->text) {
            $this->content .= ' '.$this->text;
          }
          $this->content .= "\n";
        break;
        case 'text':
        case 'date':
        case 'email':
        case 'checkbox':
        case 'radiobutton':
        case 'hidden':
        case 'button':

          $this->content .= '<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->id.'" class="'.$this->class.'"';
          if ($this->action) {
            $this->content .= ' onclick="'.$this->action.'"';
          }
          if ($this->keydown) {
            $this->content .= ' onkeydown="'.$this->keydown.'"';
          }
          if ($this->keyup) {
            $this->content .= ' onkeyup="'.$this->keyup.'"';
          }
          if ($this->keypress) {
            $this->content .= ' onkeypress="'.$this->keypress.'"';
          }
          if ($this->blur) {
            $this->content .= ' onblur="'.$this->keyup.'"';
          }
          if ($this->input) {
            $this->content .= ' oninput="'.$this->input.'"';
          }
          if ($this->value) {
            $this->content .= ' value="'.$this->value.'"';
          }
          if ($this->disabled) {
            $this->content .= ' disabled';
          }
          $this->content .= '>';
          if ($this->text) {
            $this->content .= ' '.$this->text;
          }
          $this->content .= "\n";
        break;
        case 'comment':
          $this->content .= '<span class="'.$this->class.' comment" id="'.$this->id.'"';
          if ($this->action) {
            $this->content .= ' onclick="'.$this->action.'"';
          }
          $this->content .= '>'.$this->value;
          if ($this->text) {
            $this->content .= ' '.$this->text;
          }
          $this->content .= "</span>\n";
        break;
        case 'add':
          $this->content .= '<img id="'.$this->id.'" src="images/add_icon.gif" class="icon"';
          if ($this->action) {
            $this->content .= ' onclick="'.$this->action.'"';
          }
          $this->content .= ' alt="Click to add a '.$this->addName.'" title="CLick to add a '.$this->addName.'"/>';
          if ($this->text) {
            $this->content .= ' '.$this->text;
          }
          $this->content .= "\n";
        break;
      }
    }
    
    public function addOption($option) {
      array_push($this->options, $option);
    }
    
    public function removeOption($value) {
      $cmp = cmpOption($value, true);
      $this->options = array_filter($this->options, $cmp);
    }
    
  }
?>