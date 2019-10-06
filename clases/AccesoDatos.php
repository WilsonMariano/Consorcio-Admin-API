<?php

require_once __DIR__ . '/../credentials.php';

class AccesoDatos{
    private static $ObjetoAccesoDatos;
    private $objetoPDO;
 
    private function __construct(){
        //***************** CONFIGURAR CONEXIÓN EN LAS SIGUIENTES VARIABLES *****************
            // Definidos en credentials.php
            $myServer 	= HOST;                
            $myDBName 	= DBNAME;
            $myUser 	= USERNAME;
            $myPassWord = PASSWORD;
        //***********************************************************************************    
        try{
            $this->objetoPDO = new PDO('mysql:host=' . $myServer .';dbname='. $myDBName .';charset=utf8', $myUser, $myPassWord, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } 
        catch (PDOException $e){ 
            print "Error!: " . $e->getMessage(); 
            die();
        }
    }
 
    public function RetornarConsulta($sql){ 
        return $this->objetoPDO->prepare($sql); 
    }
    
    public function beginTransaction(){ 
        return $this->objetoPDO->beginTransaction(); 
    }
    
    public function commit(){ 
        return $this->objetoPDO->commit(); 
    }
    
    public function rollBack(){ 
        return $this->objetoPDO->rollBack(); 
    }
	
	public function Query($sql){ 
        return $this->objetoPDO->query($sql); 
    }
    
    public function RetornarUltimoIdInsertado(){ 
        return $this->objetoPDO->lastInsertId(); 
    }
 
    public static function dameUnObjetoAcceso(){ 
        if (!isset(self::$ObjetoAccesoDatos)) {          
            self::$ObjetoAccesoDatos = new AccesoDatos(); 
        } 
        return self::$ObjetoAccesoDatos;        
    }
 
     // Evita que el objeto se pueda clonar
    public function __clone(){ 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }

}//class
