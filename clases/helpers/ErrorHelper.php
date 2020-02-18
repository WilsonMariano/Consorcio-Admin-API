<?php

require_once __DIR__ . "\BaseHelper.php";

class ErrorHelper extends BaseHelper{

private const _ARRAY = "array";

public static function LogError ($methodName, $data, $e){

    if(gettype($data) != self::_ARRAY)
        $entityName = get_class($data);
    else 
        $entityName = self::_ARRAY;

    $error = new \stdClass();
    $error->method = $methodName;
    $error->entity = $entityName;
    $error->data = $data;

    error_log(json_encode($e) . "-" . json_encode($error) , 0);
}

}//class