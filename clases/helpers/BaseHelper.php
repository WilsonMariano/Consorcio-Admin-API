<?php

abstract class BaseHelper {

    public static function MyEcho($text){
      echo "\xA";
      echo $text . "\xA";
    }
    
    public static function MyVarDump($data){
      echo "\xA";
      var_dump($data);
      echo "\xA";
    }

}