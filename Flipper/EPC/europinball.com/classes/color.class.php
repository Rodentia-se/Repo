<?php

  class color extends base {
        
    public static $instances;
    public static $arrClass = 'colors';

    public static $select = '
      select 
        o.id as id,
        o.name as name,
        o.r as r,
        o.g as g,
        o.b as b,
        concat(LPAD(HEX(o.r), 2, 0), LPAD(HEX(o.g), 2, 0), LPAD(HEX(o.b), 2, 0)) as rgb,
        o.c as c,
        o.m as m,
        o.y as y,
        o.k as k
      from color o
    ';
    public static $parents = array();

    public static $children = array();

  }

?>