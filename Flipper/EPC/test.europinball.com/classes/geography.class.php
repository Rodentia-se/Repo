<?php

  abstract class geography extends base {

    public static $parents = array();

    public function getChildrenTables() {
      $tabs = new tabs(NULL, 'childrenTabs');
        foreach (static::$infoChildren as $childArrayClass) {
          $childrenDiv = $tabs->addDiv($childArrayClass.'Div');
          $children = $childArrayClass($this);
          foreach ($children as $child) {
            $rows[] = getRegRow();
          }
          $table = $childrenDiv->addTable();
        }
        $playerDiv = $tabs->addDiv('playerDiv');
      //}
      return $tabs;
    }

  }
?>