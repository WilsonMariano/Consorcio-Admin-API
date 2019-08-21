<?php

require_once "AccesoDatos.php";
require_once "Usuarios.php";
require_once "Adherentes.php";
require_once "UF.php";
 




class Funciones
{

//****************************************************************
//**********   AGREGAR LAS CLASES AL SWITCH  *********************
//****************************************************************

     public static function getObjEntidad($EntityName){
        switch (trim($EntityName)) 
        {

		  case "usuarios":
				$objEntidad = new Usuarios();
				break;
		  case "adherentes":
				$objEntidad = new Adherentes();
				break;
		  case "UF":
				$objEntidad = new UF();
				break;


        }//switch   
        return  $objEntidad;
    }




//******************************************************************
//************* METODOS DE CLASE *****   NO MODIFICAR  *************
//******************************************************************


	 public static function GetAll($EntityName)	{

    	$objetoAccesoDato = \AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta =$objetoAccesoDato->RetornarConsulta('select * from ' .$EntityName);
		$consulta->execute();		
		$arrObjEntidad= $consulta->fetchAll(\PDO::FETCH_CLASS, $EntityName );	
		
		return $arrObjEntidad;
		
	}
	 
	 
	public static function UpdateOne($datosRecibidos)	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = Funciones::getObjEntidad ($datosRecibidos['t']);
	
		//Consulto los atributos de la clase para armar la query	    	
		$vars_clase = get_class_vars(get_class($objEntidad));
		$myQuery = "update " . $datosRecibidos['t'] . " set ";
		foreach ($vars_clase as $nombre => $valor) {
				//Armo la query UPDATE según los atributos de mi objeto
				if ($nombre != null and $nombre != "id"){$myQuery .= $nombre . "=:" . $nombre . ",";}
				//Bindeo los atributos de mi objeto con el array recibido por queryString para configurar parametros en setQueryParams(..)
				$objEntidad->$nombre = $datosRecibidos[$nombre];
		}
		
		$myQuery = rtrim($myQuery,",")." where  id=:id ";
		$consulta =$objetoAccesoDato->RetornarConsulta($myQuery);
		$objEntidad->setQueryParams($consulta,$objEntidad);
		
		return $consulta->execute();
			
	}


	public static function GetOne($idParametro,$EntityName) {	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = Funciones::getObjEntidad ($EntityName);
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $EntityName . " where id =:id");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject($EntityName);
		
		return $objEntidad;						
	 }


	public static function DeleteOne($idParametro,$EntityName)	{	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from " . $EntityName ." WHERE id=:id");	
        $consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount();
	}


	public static function InsertOne($datosRecibidosQS,$datosRecibidosBody)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
 		$objEntidad = Funciones::getObjEntidad ($datosRecibidosQS['t']);
			
	    	//Consulto los atributos de la clase para armar la query	    	
			$vars_clase = get_class_vars(get_class($objEntidad));
			$myQuery = "insert into " . $datosRecibidosQS['t'] ." (" ;
			$myQueryAux ;
			foreach ($vars_clase as $nombre => $valor) {
					//Armo la query según los atributos de mi objeto
					if ($nombre != null ){
						$myQuery .= $nombre .  ",";
						$myQueryAux .= ":".$nombre.","; 
						//Bindeo los atributos de mi objeto con el array recibido por queryString para configurar parametros en setQueryParams(..)
						$objEntidad->$nombre = $datosRecibidosBody[$nombre];
						}
			}
			
			$myQuery = rtrim($myQuery,",").") values (" . rtrim($myQueryAux,",") . ")" ;
							
			$consulta =$objetoAccesoDato->RetornarConsulta($myQuery);
			$objEntidad->setQueryParams($consulta,$objEntidad);
			$consulta->execute();
			
			return $objetoAccesoDato->RetornarUltimoIdInsertado();
			
	}//InsertOne
   
  


}//Class
