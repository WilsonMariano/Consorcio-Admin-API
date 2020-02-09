<?php


class MovimientosFP
{
	// Atributos
	public $id;
	public $idMovimientoFondoEsp;
	public $idLiquidacionesGlobales;
	
	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->id = $arrData['idMovimientoFondoEsp'];
			$this->id = $arrData['idLiquidacionesGlobales'];
		} 
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'  ,$objEntidad->id  ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idMovimientoFondoEsp'     ,$objEntidad->idMovimientoFondoEsp     ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionesGlobales'  ,$objEntidad->idLiquidacionesGlobales  ,\PDO::PARAM_INT);
	}

}//class