<?php

require_once __DIR__ . "\BaseHelper.php";

class ErrorHelper extends BaseHelper{

public static function LogError ($errorCode, $obj, $e){
    $entityName = get_class($obj);

    $error = new \stdClass();
    $error->method = $errorCode;
    $error->entity =  class_exists($entityName) ? $entityName : stdClass::Class;
    $error->data = $obj;

    error_log(json_encode($e) . "-" . json_encode($error) , 0);
}

}//class