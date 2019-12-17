<?php

require_once "AccesoDatos.php";

class NotasDebito{

	//Atributos
	public $id;
	public $idLiquidacion;
	public $fechaVencimiento;
	public $fechaEmision;
	public $observaciones;
	
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacion = $arrData['idLiquidacion'];
			$this->fechaVencimiento = $arrData['fechaVencimiento'];
			$this->fechaEmision = $arrData['fechaEmision'];
			$this->observaciones = $arrData['observaciones'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id', $objEntidad->id, \PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacion'  ,$objEntidad->idLiquidacion  ,\PDO::PARAM_INT);
		$consulta->bindValue(':fechaVencimiento' ,$objEntidad->fechaVencimiento ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaEmision'     ,$objEntidad->fechaEmision     ,\PDO::PARAM_STR);
		$consulta->bindValue(':observaciones'     ,$objEntidad->observaciones     ,\PDO::PARAM_STR);
	}

}//class