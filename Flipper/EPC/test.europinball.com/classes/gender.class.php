<?php

  class gender extends base {
        
    public static $instances;
    public static $arrClass = 'genders';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.gender_def as shortName,
        o.gender_def as acronym,
        o.comment as comment
      from gender o
    ';
    
    public static $parents = array();

    public static $children = array(
      'player' => 'gender',
      'volunteer' => 'gender'
    );

    public function __construct($data = NULL, $search = NOSEARCH, $depth = NULL) {
      parent::__construct($datam, $search, $depth);
      $this->name = ucfirst($this->name);
    }

  }

?>