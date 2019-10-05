<?php

class Helper
{

    public static function GetNullOrValue($value){
        if(isset($value) && !empty($value) && strlen($value)>1)
            return $value;
        else 
            return NULL;
    }


}//class