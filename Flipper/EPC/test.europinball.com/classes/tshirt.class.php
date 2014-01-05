<?php

  class tshirt extends base {
        
    public static $instances;
    public static $arrClass = 'tshirts';
    public static $table = 'tournamentTShirt';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.name as fullName,
        o.name as shortName,
        ifnull(o.number, 0) as number,
        ifnull(o.soldOnSite, 0) as soldOnSite,
        concat(tc.name, tz.id) as sortName,
        tc.id as color_id,
        tc.name as colorName,
        tz.id as size_id,
        tz.name as sizeName,
        ts.id as tshirt_id,
        o.tournamentEdition_id as tournamentEdition_id
      from tournamentTShirt o 
        left join tshirt ts on o.tshirt_id = ts.id
        left join color tc on ts.color_id = tc.id
        left join size tz on ts.size_id = tz.id
    ';
    
    public static $parents = array(
      'tournamentEdition' => 'tournament',
      'color' => 'color',
      'size' => 'size'
    );
    
    public static $children = array();

    public function __construct($data = NULL, $search = config::NOSEARCH, $depth = NULL) {
      parent::__construct($data, $search, $depth);
      $tshirtOrders = tshirtOrders($this);
      debug(count($tshirtOrders), 'count '.$this->id);
      $this->reservers = count($tshirtOrders->getAllOf('person_id'));
      $this->reserved = array_sum($tshirtOrders->getAllOf('number'));
      $this->delivered = array_sum($tshirtOrders->getAllOf('numberDelivered'));
debug($tshirtOrders->getAllOf('numberDelivered'));
      $this->sold = (+ $this->reserved + $this->soldOnSite);
      $this->handedOut = (+ $this->delivered + $this->soldOnSite); 
      $this->inStock = (+ $this->number - $this->handedOut);
      $this->forSale = (+ $this->number - $this->sold);
    }

  }

?>