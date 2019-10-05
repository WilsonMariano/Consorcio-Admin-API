<?php

foreach (glob("clases/*.php") as $filename){
    require_once $filename;
}


class Funciones
{

	private static function GetObjEntidad($entityName){
		$class = $entityName;
		// $class = 'Class'.$entityName;
		$object = new $class();
		return $object;
	}

	public static function  IsDuplicated($entityObject){
		$entityName = get_class($entityObject);
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $entityName . " where id =:id");
		$consulta->bindValue(':id', $entityObject->id, PDO::PARAM_INT);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}


	public static function GetAll($entityName){
    	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta =$objetoAccesoDato->RetornarConsulta('select * from ' .$entityName);
		$consulta->execute();		
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;
	}//GetAll

	
	public static function GetPagedWithOptionalFilter($entityName, $column1, $text1, $column2, $text2, $rows, $page){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

		$consulta =$objetoAccesoDato->RetornarConsulta(
			"call spGetPagedWithOptionalFilter('$entityName', '$column1', '$text1', '$column2', '$text2', $rows, $page, @o_total_rows)");

		$consulta->execute();
		$arrResult= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		$consulta->closeCursor();
		
		$output = $objetoAccesoDato->Query("select @o_total_rows as total_rows")->fetchObject();
			
		$result = new \stdClass();
		$result->total_pages = ceil(intval($output->total_rows)/intval($rows));
		$result->total_rows = $output->total_rows;
		$result->data = $arrResult;
		
 		return $result;					
	}
	
	 
	// public static function GetWithPaged($entityName,$rows,$page){
		
		// //Obtengo los datos con paginado
		// $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		// $consulta =$objetoAccesoDato->RetornarConsulta("call spGetViewWithpaged('$entityName' ,$rows , $page)" );
		// $consulta->execute();
		// $arrResult= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		// $consulta->closeCursor();
	
		// //Calculo el total de páginas necesarias
		// $consulta =$objetoAccesoDato->RetornarConsulta("select count(*) as rows_q from " . $entityName);
		// $consulta->execute();
		// $row_quantity =$consulta->fetchObject();	
		
		// $result = new \stdClass();
		
		// // Se utliza funcion ceil() de php para rounding
		// $result->total_pages = ceil(intval($row_quantity->rows_q)/intval($rows));
		// $result->total_rows = $row_quantity->rows_q;
		// $result->data = $arrResult;
		
		// return $result;					
		
	// } 


	public static function GetOne($idParametro,$entityName){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = self::GetObjEntidad ($entityName);
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $entityName . " where id =:id");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject($entityName);
		
		return $objEntidad;						
	}//GetOne	 
	 
	 
	public static function UpdateOne($datosRecibidosQS,$datosRecibidosBody){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
 		$objEntidad = self::GetObjEntidad ($datosRecibidosQS['t']);
	
		//Consulto los atributos de la clase para armar la query	    	
		$vars_clase = get_class_vars(get_class($objEntidad));
		$myQuery = "update " . $datosRecibidosQS['t'] . " set ";
		foreach ($vars_clase as $nombre => $valor) {
			//Armo la query UPDATE según los atributos de mi objeto
			if ($nombre != null and $nombre != "id"){
				$myQuery .= $nombre . "=:" . $nombre . ",";
			}
			//Bindeo los atributos de mi objeto con el array recibido por queryString para configurar parametros en setQueryParams(..)
			$objEntidad->$nombre = $datosRecibidosBody[$nombre];
		}
		
		$myQuery = rtrim($myQuery,",")." where  id=:id ";
		$consulta =$objetoAccesoDato->RetornarConsulta($myQuery);
		$objEntidad->setQueryParams($consulta,$objEntidad);
		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
		
	}//UpdateOne


	public static function InsertOne($entityName, $datosRecibidosBody)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
 		$objEntidad = self::GetObjEntidad ($entityName);
			
		//Consulto los atributos de la clase para armar la query	    	
		$vars_clase = get_class_vars(get_class($objEntidad));
		$myQuery = "insert into " . $entityName ." (" ;
		$myQueryAux = "";
		foreach ($vars_clase as $nombre => $valor) {
			//Armo la query según los atributos de mi objeto. Excluyo el campo id ya que es autoincremental.
			if ($nombre != null && $nombre != "id" ){
				$myQuery .= $nombre .  ",";
				$myQueryAux .= ":".$nombre.","; 
				//Bindeo los atributos de mi objeto con el array recibido por queryString para configurar parametros en setQueryParams(..)
				$objEntidad->$nombre = $datosRecibidosBody[$nombre];
			}
		}
		
		$myQuery = rtrim($myQuery,",").") values (" . rtrim($myQueryAux,",") . ")" ;
						
		$consulta =$objetoAccesoDato->RetornarConsulta($myQuery);
		$objEntidad->setQueryParams($consulta,$objEntidad,false);
		$consulta->execute();
		
		return $objetoAccesoDato->RetornarUltimoIdInsertado()> 0 ? true : false;
	}//InsertOne
   
   
	public static function DeleteOne($idParametro,$entityName){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from " . $entityName ." WHERE id=:id");	
        $consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}//DeleteOne

}//Class
