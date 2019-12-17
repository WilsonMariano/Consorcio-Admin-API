<?php

require_once "AccesoDatos.php";

class GastosExpensas{

	//Atributos
	public $id;
	public $idExpensa;
	public $idGastosLiquidaciones;
	public $monto;
	
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idExpensa = $arrData['idExpensa'];
			$this->idGastosLiquidaciones = $arrData['idGastosLiquidaciones'];
			$this->monto = $arrData['monto'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id', $objEntidad->id, \PDO::PARAM_INT);
		
		$consulta->bindValue(':idExpensa'              , $objEntidad->idExpensa       , \PDO::PARAM_INT);
		$consulta->bindValue(':idGastosLiquidaciones'  , $objEntidad->idGastosLiquidaciones , \PDO::PARAM_INT);
		$consulta->bindValue(':monto'		           , $objEntidad->monto                 , \PDO::PARAM_STR);
	}

}//class