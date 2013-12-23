<?php

  class tabs extends html {
    
    public function __construct($contents = NULL, $id = NULL, $class = NULL, array $params = NULL) {
      
      parent::__construct('div', NULL, $params, $id, $class, NULL);
    }
//    html public function __construct($element = 'span', $contents = NULL, array $params = NULL, $id = NULL, $class = NULL, array $css = NULL, $indents = 0) {
    
    public function addContent($content = NULL, $replace = FALSE, $index = FALSE) {
      if ($replace) {
        $this->delContent($replace);
      }
      if (is_array($content)) {
        $return = TRUE;
        foreach($content as $part) {
          $result = $this->addContent($part, FALSE, $index);
          if (!$result) {
            $return = FALSE;
          }
        }
        return $return;
      } else if (isHtml($content)) {
        if (get_class($content) == 'div') {
          $div = $content;
          $div->params['title'] = ($div->title) ? $div->title : ucfirst($div->id);
        } else {
          $div = new div((($content->id) ? $content->id.'_tab' : html::newId(NULL, '_tab')));
          $div->params['title'] = ($content->title) ? $content->title : ucfirst(preg_replace('/_tab$/', '', $div->id));
          $div->addContent($content);
        }
      } else {
        $div = new div(html::newId(NULL, '_tab'));
        $div->params['title'] = preg_replace('/_tab$/', '', ucfirst($div->id));
        $div->addContent($content);
      }
      return parent::addContent($div, $replace, $index);
    }

    protected function getHeader($index = NULL, $string = TRUE) {
      if ($this->contents) {
        foreach ($this->contents as $content) {
          $ul = new ul();
          $li = $ul->addLi();
          $li->addLink('#'.$content->id, $content->title);
          return ($string) ? $ul->getHtml() : $ul;
        }
      }
      return NULL;
    }
    
    protected function getFooter($index = NULL, $string = TRUE) {
      $script = new scriptCode('
        $(document).ready(function() {
          try {
            var tabIndex = dataStore.getItem("'.$this->id.'Index");
          } catch(e) {
            var '.$this->id.'Index = 0;
          };
          tabIndex = (parseInt('.$this->id.'Index)) ? parseInt('.$this->id.'Index) : 0;
          $("#'.$this->id.'").tabs({
            active: '.$this->id.'Index,
            activate: function(event, ui) {
              dataStore.setItem("'.$this->id.'Index", ui.newTab.parent().children().index(ui.newTab));
              var firstField = ui.newPanel.find("input[type=text],textarea,select").filter(":visible:first");
              if (firstField) {
                firstField.focus();
              }
            },
            create: function(event, ui) {
              var firstField = ui.panel.find("input[type=text],textarea,select").filter(":visible:first");
              if (firstField) {
                firstField.focus();
              }
            }
          });
        });
      ');
      return ($string) ? $script->getHmtl() : $script;
    }

  }
  
?>