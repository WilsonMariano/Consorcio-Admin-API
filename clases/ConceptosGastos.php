<?php

require_once "AccesoDatos.php";

class ConceptosGastos
{

	//	Atributos
	public $id;
	public $codigo;
	public $conceptoGasto;
 

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':codigo'	       ,$objEntidad->codigo         ,\PDO::PARAM_STR);
		$consulta->bindValue(':conceptoGasto'  ,$objEntidad->conceptoGasto  ,\PDO::PARAM_STR);
	}


 	public static function  IsDuplicated($entityObject){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from ConceptosGastos where codigo =:codigo");
		$consulta->bindValue(':codigo', $entityObject->codigo, PDO::PARAM_STR);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}


	public static function Insert($entityObject){
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into conceptosGastos(codigo, conceptoGasto) values(:codigo,:conceptoGasto)");
		self::setQueryParams($consulta,$entityObject,false);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	
	}

	public static function GetOne($codigo){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new ConceptosGastos();
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from conceptosGastos where codigo =:codigo");
		$consulta->bindValue(':codigo', $codigo , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject("ConceptosGastos");
		
		return $objEntidad;						
	}//GetOne	



}//class