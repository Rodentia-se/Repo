<?php

  abstract class geography extends base {

    public static $parents = array();

    public function getChildrenTabs($tournament = 'active') {
      $tournament = getTournament($tournament);
      $tabs = new tabs(NULL, 'childrenTabs');
        foreach (static::$infoChildren as $childArrayClass) {
          $children = $childArrayClass($tournament, $this);
          if ($children && count($children) > 0) {
            $childrenDiv = $tabs->addDiv($childArrayClass.'Div');
            $childrenDiv->addContent($children->getTable());
          }
        }
      //}
      return $tabs;
    }

  }
?>