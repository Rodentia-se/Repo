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

    // @todo: Fix children
/*
    public static $children = array(
      'game' => 'manufacturer',
      'machine' => 'manufacturer'
    );
*/

    public static $infoProps = array(
      'name',
      'URL' => 'getUrlLink'
    );
    
    public static $infoChildren = array(
      'machines'
    );

    public function getUrlLink() {
      return $this->getLink('url');
    }

    public function getLink($type = 'object', $anchor = true, $thumbnail = false, $preview = false, $defaults = true) {
      switch ($type) {
        case 'url':
          if ($this->url) {
            $url = $this->url;
            $text = (strlen($this->url) < 40) ? $this->url : substr($this->url, 0, 37).'...';
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_blank">'.$text.'</a>' : $url;
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }

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