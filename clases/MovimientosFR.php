<?php

class MovimientosFR
{
	// Atributos
	public $id;
	public $idMovimientoFondoEsp;
	public $idGastoLiquidacion;
	
	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idMovimientoFondoEsp = $arrData['idMovimientoFondoEsp'];
			$this->idGastoLiquidacion = $arrData['idGastoLiquidacion'];
		} 
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'  ,$objEntidad->id  ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idMovimientoFondoEsp'  ,$objEntidad->idMovimientoFondoEsp  ,\PDO::PARAM_INT);
		$consulta->bindValue(':idGastoLiquidacion'    ,$objEntidad->idGastoLiquidacion    ,\PDO::PARAM_INT);
	}

}//class