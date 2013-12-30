<?php

  class manufacturer extends base {
        
    public static $instances;
    public static $arrClass = 'manufacturers';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.shortName as shortName,
        o.shortName as acronym,
        o.url as url,
        o.comment as comment
      from manufacturer o
    ';
    
    public static $parents = array();

    public static $children = array(
      'game' => 'manufacturer',
      'machine' => 'manufacturer'
    );

    public static $infoChildren = array(
      'games'
    );

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