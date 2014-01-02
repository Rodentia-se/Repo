<?php

  class doublebox extends html {
    
    public function __construct($select, $combobox = TRUE, $inputId = NULL, $inputLabel = NULL, array $params = NULL) {
      $id = ($params['id']) ? $id : $select->id.'Div';
      $inputId = ($inputId) ? $inputId : $select->id.'Add';
      $inputLabel = ($inputLabel) ? $inputLabel : (($select->label) ? $select->label->getText() : 'New');
      $inputDiv = new div($inputId.'Div', 'hidden');
        $input = $inputDiv->addInput($this->id.'Add', $label, 'text', $inputLabel, $params);
        $closeIcon = new img(config::$baseHref.'/images/cancel.png', 'Click to remove '.strtolower($inputLabel), array('class' => 'closeIcon'));
        $closeIcon->addClick('
          $("#'.$inputDiv->id.'").hide();
          $("#'.$selectDiv_id.'").show().find("input").first().focus().select();
          $("#'.$input->id.'").val("'.$label.'");
        ');
        $inputDiv->addFooter($closeIcon);
      //}
      $this->addBefore($inputDiv);
      if ($combobox) {
        $select->addCombobox();
      }
      $addIcon = new img(config::$baseHref.'/images/add_icon.gif', 'Click to add '.strtolower($inputLabel), array('class' => 'addIcon'));
      $addIcon->addCLick('
        $("#'.$selectDiv_id.'").hide();
        $("#'.$inputDiv->id.'").show().find("input").first().focus().select();
      ');
      $this->addFooter($addIcon);
      parent::__construct('div', $select, $params, $id);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {


/*
    
      public function addDoublebox($select) {
      $div = new div();
        $selectDiv_id = (isHtml($selectDiv)) ? $selectDiv->id  : (($selectDiv) ? $selectDiv : $this->id.'Div');
        $inputDiv = $profileDiv->addDiv($selectDiv_id.'Add', 'hidden');
          $input = $div->addInput($this->id.'Add', $label, 'text', $label, $params);
        //}
        $addIcon = new img(config::$baseHref.'/images/add_icon.gif', 'Click to add '.strtolower($label), array('class' => 'addIcon'));
        $addIcon->addCLick('
          $("#'.$selectDiv_id.'").hide();
          $("#'.$inputDiv->id.'").show().find("input").first().focus().select();
        ');
        $this->addAfter($addIcon)
        $closeIcon = $div->addImg(config::$baseHref.'/images/cancel.png', 'Click to remove '.strtolower($label), array('class' => 'closeIcon'));
        $closeIcon->addClick('
          $("#'.$inputDiv->id.'").hide();
          $("#'.$selectDiv_id.'").show().find("input").first().focus().select();
          $("#'.$input->id.'").val("'.$label.'");
        ');
        return $inputDiv;      
    }
    
    public function addManualInput($label = 'New', $selectDiv = NULL, array $params = NULL) {
    }
    
    
 /*   
    
    
getInput(NULL, $prefix.'city', 'city', 'text', 'edit', 'New city', TRUE)    
    public function addInput($value = NULL, $id = NULL, $name = NULL, $type = 'text', $class = NULL, $label = TRUE, $close = FALSE, $disabled = FALSE) {
      $input = self::getInput($value, $id, $name, $type, $class, $label, $close, $disabled);
      $this->addContent($input);
      return $input;
    }
      if ($add) {
        $select .= page::getIcon('images/add_icon.gif', 'add_'.static::$objClass, 'addIcon editIcon', 'Click to add new '.static::$objClass);
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
      $div = $profileDiv->addDiv($divId);
        $new = $div->addInput($this->id.'Add', NULL, 'text', $label, array('id' => $prefix.$geo, 'class' => $editClass));
        $new->hide();
    public function addAddIcon($id = NULL, $class = NULL, $label = NULL) {
      $icon = self::getAddIcon($id, $class, $label);
      $this->addContent($icon);
      return $icon;
    }
    public static function getAddIcon($id = NULL, $class = NULL, $label = NULL) {
      return self::getIcon(config::$baseHref.'images/add_icon.gif', 'add_'.$id, 'addIcon editIcon '.$class, 'Click to add '.$label);
    }
    public function addCloseIcon($id = NULL, $class = NULL, $label = NULL) {
      $icon = self::getCloseIcon($id, $class, $label);
      $this->addContent($icon);
      return $icon;
    }
    public static function getCloseIcon($id = NULL, $class = NULL, $label = NULL) {
      return self::getIcon(config::$baseHref.'images/cancel.png', 'close_'.$id, 'closeIcon editIcon '.$class, 'Click to remove '.$label);
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
*/

  
    
  }
  
?>