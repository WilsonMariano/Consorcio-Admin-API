<?php

require_once "AccesoDatos.php";

class Expensas
{
	// Atributos
	public $id;
	public $idLiquidacion;
	public $idLiquidacionGlobal;
	public $coeficiente;

	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacion = $arrData['idLiquidacion'];
			$this->idLiquidacion = $arrData['idLiquidacionGlobal'];
		} else {
			$this->coeficiente = 0;
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
		$consulta->bindValue(':coeficiente'   	       ,$objEntidad->coeficiente          ,\PDO::PARAM_STR);
	}

}//class