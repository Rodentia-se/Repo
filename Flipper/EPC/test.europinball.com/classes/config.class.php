<?php

  class config {

    public static $dbhost = 'localhost';
    public static $dbname = 'epc_test';
    public static $dbuser = 'epc';
    public static $dbpass = 'vLdqLYyvxSZermEv';
    public static $charset = 'utf8';
    public static $dbconfig = FALSE; // Set to TRUE to keep the rest of the config in the database
    
    // The below will not be used if $dbconfig is set to TRUE
    public static $parentDepth = 3;  // The depth for automatic population of parent objects.
    
    
    
    // Do not change anything below this line!
    
    
    
  }

?>