<?php

require_once __DIR__ . "\BaseHelper.php";

class PDOHelper extends BaseHelper{

public static function FetchAll($consulta, $entityName = ""){
    if(class_exists($entityName))
        return $consulta->fetchAll(PDO::FETCH_CLASS, $entityName);	
    else
        return $consulta->fetchAll(PDO::FETCH_ASSOC);	
}

public static function FetchObject($consulta, $entityName = ""){
    if (class_exists($entityName))
        return $consulta->fetchObject($entityName);
    else
        return $consulta->fetchObject();			
}

}//class