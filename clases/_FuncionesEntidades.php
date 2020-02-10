<?php

require_once "helpers\PDOHelper.php";
require_once "helpers\ErrorHelper.php";
require_once "enums\ErrorEnum.php";
foreach (glob("clases\*.php") as $filename)
    require_once $filename;

class Funciones{

	public static function GetObjEntidad($entityName, $apiParamsBody = null){
		return new $entityName($apiParamsBody);
	}

	/**
	 * Valida si un registo ya existe previamente en la BD. Admite un param opcional para validar contra una columna
	 * en especial, de no recibirlo por default valida contra la columna "id".
	 */
	public static function Exists($entityObject, $column = "id"){

		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
			$entityName = get_class($entityObject);		
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $entityName . 
				" where " . $column . "=:" . $column);
			$consulta->bindValue(':' . $column, $entityObject->$column, PDO::PARAM_INT);
			$consulta->execute();
	
			return $consulta->rowCount() > 0 ? true : false;

		}catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $objEntidad , $e);		 
			throw new ErrorException("No se pudo validar la existencia de una entidad del tipo " . $entityName);
		}
	}

	public static function GetAll($entityName){

		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta('select * from ' .$entityName);
			$consulta->execute();	
			$arrObjEntidad= PDOHelper::FetchAll($consulta, $entityName);	
			
			return $arrObjEntidad;

		}catch(Exception $e){
			ErrorHelper::LogError(ErrorEnum::GenericGet, $objEntidad , $e);		 
			throw new ErrorException("No se pudieron recuperar entidades del tipo " . $entityName);
		}
	}
	
	public static function GetPagedWithOptionalFilter($entityName, $column1, $text1, $column2, $text2, $rows, $page){

		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

			$consulta =$objetoAccesoDato->RetornarConsulta("call spGetPagedWithOptionalFilter('$entityName', '$column1', 
				'$text1', '$column2', '$text2', $rows, $page, @o_total_rows)");
	
			$consulta->execute();
			$arrResult= PDOHelper::FetchAll($consulta);	
			$consulta->closeCursor();
			
			$output = $objetoAccesoDato->Query("select @o_total_rows as total_rows")->fetchObject();
				
			//Armo la respuesta
			$result = new \stdClass();
			//Uso ceil() para redondear de manera ascendente
			$result->total_pages = ceil(intval($output->total_rows)/intval($rows));
			$result->total_rows = $output->total_rows;
			$result->data = $arrResult;
			
			return $result;	

		}catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $objEntidad , $e);		 
			throw new ErrorException("No se pudieron recuperar entidades del tipo " . $entityName);
		}
	}
	
	public static function GetOne($idParametro, $entityName){	
		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . $entityName . " where id =:id");
			$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
			$consulta->execute();
	
			$objEntidad = PDOHelper::FetchObject($consulta, $entityName);
			return $objEntidad;	

		}catch(Exception $e){
			ErrorHelper::LogError(ErrorEnum::GenericGetOne, $objEntidad , $e);		 
			throw new ErrorException("No se pudo insertar una entidad del tipo " . $entityName);
		}
	}	 
	 
	/**
	 * Update Genérico. Retorna bool indicando si se modifico algún registro.
	 */
	public static function UpdateOne($objEntidad){
		try {  
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
		
		}catch(Exception $e){
			ErrorHelper::LogError(ErrorEnum::GenericUpdate, $objEntidad , $e);		 
			throw new ErrorException("No se pudo actualizar una entidad del tipo " . $entityName);
		}
	}

	/**
	 * Insert genérico, retorna el ID generado por la BD
	 */
	public static function InsertOne($objEntidad, $includePK = false)
	{
		try {  
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

		}catch(Exception $e){
			ErrorHelper::LogError(ErrorEnum::GenericInsert, $objEntidad , $e);		 
            throw new ErrorException("No se pudo insertar una entidad del tipo " . $entityName);
		}
	}
	
	/**
	 * Delete genérico. Retorna bool indicando si se eliminó algún registro.
	 */
	public static function DeleteOne($idParametro,$entityName){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from " . $entityName ." WHERE id=:id");	
		$consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

}//Class

