<?php

require_once "AccesoDatos.php";

class FondosEspeciales
{
	// Atributos
	public $id;
	public $idLiquidacion;
	public $idLiquidacionGlobal;
	public $tipoLiquidacion;
	
	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacion = $arrData['idLiquidacion'];
			$this->idLiquidacionGlobal = $arrData['idLiquidacionGlobal'];
			$this->tipoLiquidacion = $arrData['tipoLiquidacion'];
		} 
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacion'          ,$objEntidad->idLiquidacion        ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionGlobal'    ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
		$consulta->bindValue(':tipoLiquidacion'        ,$objEntidad->tipoLiquidacion      ,\PDO::PARAM_STR);
	}

}//class