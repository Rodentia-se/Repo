<?php

  abstract class geography extends base {
    
    public $streetAddress;
    public $zipCode;
    public $continent_id;
    public $continent;
    public $parentCountry_id;
    public $parentCountry;
    public $country_id;
    public $country;
    public $parentRegion_id;
    public $parentRegion;
    public $region_id;
    public $region;
    public $city_id;
    public $city;
    public $altName;
    public $capitalCity_id;
    public $capitalCity;
    public $latitude;
    public $longitude;
    public $selfParent = false;
    
  }
?>