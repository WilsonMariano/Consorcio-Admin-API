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
 
		return $consulta;
	}


 	public static function  IsDuplicated($entityObject){

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from ConceptosGastos where codigo =:codigo");
		$consulta->bindValue(':codigo', $entityObject->codigo, PDO::PARAM_STR);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}


}//class