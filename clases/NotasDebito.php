<?php

require_once "AccesoDatos.php";

class NotasDebito{

	//Atributos
	public $id;
	public $idLiquidacionUF;
	public $fechaVencimiento;
	public $fechaEmision;
	
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacionUF = $arrData['idLiquidacionUF'];
			$this->fechaVencimiento = $arrData['fechaVencimiento'];
			$this->fechaEmision = $arrData['fechaEmision'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id', $objEntidad->id, \PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionUF'  ,$objEntidad->idLiquidacionUF  ,\PDO::PARAM_INT);
		$consulta->bindValue(':fechaVencimiento' ,$objEntidad->fechaVencimiento ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaEmision'     ,$objEntidad->fechaEmision     ,\PDO::PARAM_STR);
	}

}//class