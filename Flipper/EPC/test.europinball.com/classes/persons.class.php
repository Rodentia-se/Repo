<?php

  class persons extends players {
    
    public static $objClass = 'person';
    
    public function getLink($type = 'object') {
      switch ($type) {
        case 'ifpa':
          if ($this->ifpa_id) {
            return '<a href="http://www.ifpapinball.com/player.php?player_id='.$this->ifpa_id.'" target="_new">'.(($this->ifpaRank && $this->ifpaRank != 0) ? $this->ifpaRank : 'Unranked').'</a>';
          } else {
            return 'Unranked';
          }
        break;
        default:
          parent::getLink($type);
        break;
      }
    }

  }

?>