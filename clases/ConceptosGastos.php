<?php

require_once "AccesoDatos.php";

class ConceptosGastos
{

	//Atributos
	public $id;
	public $codigo;
	public $conceptoGasto;
 
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"] ?? null;
			$this->codigo = $arrData["codigo"];
			$this->conceptoGasto = $arrData["conceptoGasto"];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':codigo'	       ,$objEntidad->codigo         ,\PDO::PARAM_STR);
		$consulta->bindValue(':conceptoGasto'  ,$objEntidad->conceptoGasto  ,\PDO::PARAM_STR);
	}

	/**
	 * (Bool) Valida si ya existe un ConceptoGasto, consultando por campo código.
	 * Recibe por parámetro el objeto ConceptoGasto a validar.
	 */
 	public static function  IsDuplicated($entityObject){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from ConceptosGastos where codigo =:codigo");
		$consulta->bindValue(':codigo', $entityObject->codigo, PDO::PARAM_STR);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	 }

	/**
	 * Devuelve un objeto ConceptoGasto , buscando por el campo código.
	 * Recibe por parámetro el código a buscar.
	 */
	public static function GetOne($codigo){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new ConceptosGastos();
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from conceptosGastos where codigo =:codigo");
		$consulta->bindValue(':codigo', $codigo , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject("ConceptosGastos");
		
		return $objEntidad;						
	}

}//class