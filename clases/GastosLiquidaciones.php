<?php

require_once "AccesoDatos.php";

class GastosLiquidaciones
{

	//	Atributos
	public $id;
	public $idLiquidacionGlobal;
	public $monto;
	public $codConceptoGasto;
	
	// Constructor customizado
	function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'];
			$this->idLiquidacionGlobal = $arrData['idLiquidacionGlobal'];
			$this->monto = $arrData['monto'];
			$this->codConceptoGasto = $arrData['codConceptoGasto'];
		}
	}

	public static function Insert($gasto){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into gastosliquidaciones (idLiquidacionGlobal, monto, codConceptoGasto)
			 values(:idLiquidacionGlobal, :monto, :codConceptoGasto)");
		self::setQueryParams($consulta,$gasto,false);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionGlobal'	 ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
 		$consulta->bindValue(':monto'		         ,$objEntidad->monto                ,\PDO::PARAM_STR);
		$consulta->bindValue(':codConceptoGasto'	 ,$objEntidad->codConceptoGasto     ,\PDO::PARAM_STR);
	}




}//class