<?php

foreach (glob("clases/*.php") as $filename)
    require_once $filename;

class Funciones{

	public static function GetObjEntidad($entityName, $apiParamsBody = null){
		return new $entityName($apiParamsBody);
	}

	/**
	 * Valida si un registo ya existe previamente en la BD. Admite un param opcional para validar contra una columna
	 * en especial, de no recibirlo por default valida contra la columna "id".
	 */
	public static function IsDuplicated($entityObject, $column = "id"){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$entityName = get_class($entityObject);		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $entityName . " where " . $column . "=:" . $column);
		$consulta->bindValue(':' . $column, $entityObject->$column, PDO::PARAM_INT);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

	public static function GetAll($entityName){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta('select * from ' .$entityName);
		$consulta->execute();		
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;
	}
	
	public static function GetPagedWithOptionalFilter($entityName, $column1, $text1, $column2, $text2, $rows, $page){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

		$consulta =$objetoAccesoDato->RetornarConsulta(
			"call spGetPagedWithOptionalFilter('$entityName', '$column1', '$text1', '$column2', '$text2', $rows, $page, @o_total_rows)");

		$consulta->execute();
		$arrResult= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		$consulta->closeCursor();
		
		$output = $objetoAccesoDato->Query("select @o_total_rows as total_rows")->fetchObject();
			
		//Armo la respuesta
		$result = new \stdClass();
		//Uso ceil() para redondear de manera ascendente
		$result->total_pages = ceil(intval($output->total_rows)/intval($rows));
		$result->total_rows = $output->total_rows;
		$result->data = $arrResult;
		
		return $result;					
	}
	
	public static function GetOne($idParametro,$entityName){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $entityName . " where id =:id");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();

		$objEntidad= $consulta->fetchObject($entityName);
		
		return $objEntidad;						
	}	 
	 
	public static function UpdateOne($objEntidad){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	
		//Obtengo el nombre de la clase y sus atributos
		$entityName = get_class($objEntidad);
		$arrAtributos = get_class_vars($entityName);

		//Armo la query SQL dinamicamente
		$myQuery = "update " . $entityName . " set ";
		foreach ($arrAtributos as $atributo => $valor) {
			if ($atributo != "id")
				$myQuery .= $atributo . "=:" . $atributo . ",";
		}
		$myQuery = rtrim($myQuery,",")." where  id=:id ";

		//Ejecuto la query
		$consulta =$objetoAccesoDato->RetornarConsulta($myQuery);
		$objEntidad->BindQueryParams($consulta,$objEntidad);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

	public static function InsertOne($objEntidad, $includePK = false)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
					 
		//Obtengo el nombre de la clase y sus atributos
		$entityName = get_class($objEntidad);
		$arrAtributos = get_class_vars($entityName);

		//Armo la query SQL dinamicamente
		$myQuery = "insert into " . $entityName ." (" ;
		$myQueryAux = "";
		foreach ($arrAtributos as $atributo => $valor) {
			if ($atributo != "id" || $includePK){
				$myQuery .= $atributo .  ",";
				$myQueryAux .= ":".$atributo.","; 
			}
		}
		$myQuery = rtrim($myQuery,",") . ") values (" . rtrim($myQueryAux,",") . ")" ;

		//Ejecuto la query
		$consulta =$objetoAccesoDato->RetornarConsulta($myQuery);
		$objEntidad->BindQueryParams($consulta, $objEntidad, $includePK);
		$consulta->execute();
		
		return $objetoAccesoDato->RetornarUltimoIdInsertado();	
	}
	
	public static function DeleteOne($idParametro,$entityName){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from " . $entityName ." WHERE id=:id");	
		$consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

}//Class

