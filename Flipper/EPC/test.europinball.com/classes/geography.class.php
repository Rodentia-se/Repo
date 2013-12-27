<?php

  abstract class geography extends base {

    public static $parents = array();

    public function getChildrenTables() {
      $tabs = new tabs(NULL, 'childrenTabs');
        foreach (static::$infoChildren as $childArrayClass) {
          $div = $tabs->addDiv($childArrayClass.'Div');
          $children = $childArrayClass();
        }
        $playerDiv = $tabs->addDiv('playerDiv');
      //}
    }

  }
?>