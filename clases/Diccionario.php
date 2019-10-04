<?php

require_once "AccesoDatos.php";

class Diccionario
{

	//	Atributos
	public $id;
	public $codigo;
	public $valor;
 

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':codigo'	 ,$objEntidad->codigo    ,\PDO::PARAM_STR);
		$consulta->bindValue(':valor'	 ,$objEntidad->valor    ,\PDO::PARAM_STR);
	}


 	public static function GetAll($codigo){
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"select * from diccionario where codigo like '" . $codigo . "%'");
		$consulta->execute();		
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;
	
	}


	public static function GetOne($codigo){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new Diccionario();
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from diccionario where codigo =:codigo");
		$consulta->bindValue(':codigo', $codigo , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject("Diccionario");
		
		return $objEntidad;						
	}//GetOne	


}//class