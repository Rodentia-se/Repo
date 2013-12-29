<?php

  class game extends base {
        
    public static $instances;
    public static $arrClass = 'games';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.acronym as acronym,
        o.acronym as shortName,
        o.manufacturer_id as manufacturer_id,
        o.game_ipdb_id as ipdb,
        o.game_link_rulesheet as rules,
        o.game_year_released as year
      from game o
    ';
    
    public static $parents = array(
      'manufacturer' => 'manufacturer'
    );

    public static $children = array(
      'machine' => 'game'
    );

    public static $infoProps = array(
      'name',
      'acronym',
      'manufacturer',
      'IPDB' => 'getIpdbLink',
      'rules' => 'getRulesLink',
      'year'
    );
    
    public function getIpdbLink() {
      return $this->getLink('ipdb');
    }

    public function getRulesLink() {
      return $this->getRulesLink('ipdb');
    }

    public function getLink($type = 'object', $anchor = true, $thumbnail = false, $preview = false, $defaults = true) {
      switch ($type) {
        case 'ipdb':
          if ($this->ipdb) {
            $url = 'http://www.ipdb.org/machine.cgi?id='.$this->ipdb;
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_new">'.$this->ipdb.'</a>' : $url;
        break;
        case 'rules':
          if ($this->rules) {
            $url = $this->rules;
          } else {
            return FALSE;
          }
          return ($url && $anchor) ? '<a href="'.$url.'" target="_new"><img src="'.config::$baseHref.'/images/textbook_icon.png" alt="Rules" title="Rules" class="icon"></a>' : $url;
        break;
        default:
          return parent::getLink($type, $anchor, $thumbnail, $preview, $defaults);
        break;
      }
    }



  }

?>