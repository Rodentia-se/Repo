<?php

  abstract class geography extends base {

    public static $parents = array();

    public function getChildrenTabs() {
      $tabs = new tabs(NULL, 'childrenTabs');
        foreach (static::$infoChildren as $childArrayClass) {
          $childrenDiv = $tabs->addDiv($childArrayClass.'Div');
          $children = $childArrayClass($this);
          $childrenDiv->addContent($children->getTable());
        }
      //}
      return $tabs;
    }

  }
?>