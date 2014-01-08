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
      $depth = (preg_match('/^[0-9]+$/', $depth)) ? $depth : config::$parentDepth;
      if (!self::$_db) {
        self::$_db = new db();
      }
      $this->db = self::$_db;
      if (!static::$instances)  {
        static::$instances = (property_exists($this, 'arrClass')) ? new static::$arrClass : array();
      }
      // @todo: ifpaPerson::__construct();
    }
  
    public function updateRank($rank = NULL) {
      $this->setProp('ifpaRank', (($rank) ? (int) $rank : 0));
    }

  }

?>