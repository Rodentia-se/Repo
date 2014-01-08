<?php

  class ifpaPerson extends person {

    public static $instances;
    public static $arrClass = 'ifpaPersons';

    public static $select = '
      select
        id as id,
        firstName as firstName,
        lastName as lastName,
        ifpa_id as ifpa_id,
        ifpaRank as ifpaRank
      from person
    ';

    public static $parents = array();
    public static $validators = array();
    public static $authorized = array('admin');
    

    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      // @todo: ifpaPerson::__construct();
    }
  
    public function updateRank($rank = NULL) {
      $this->setProp('ifpaRank', (($rank) ? (int) $rank : 0));
    }

  }

?>