<?php


class MovimientosFondosEsp
{
	// Atributos
	public $id;
	public $idManzana;
	public $monto;
	public $descripcion;
	public $saldo;
	public $tipoLiquidacion;
	
	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idManzana = $arrData['idManzana'];
			$this->monto = $arrData['monto'];
			$this->descripcion = $arrData['descripcion'];
			$this->saldo = $arrData['saldo'];
			$this->tipoLiquidacion = $arrData['tipoLiquidacion'];
		} 
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id',  $objEntidad->id,  \PDO::PARAM_INT);
		
		$consulta->bindValue(':idManzana',        $objEntidad->idManzana,        \PDO::PARAM_INT);
		$consulta->bindValue(':monto',            $objEntidad->monto,            \PDO::PARAM_INT);
		$consulta->bindValue(':descripcion',      $objEntidad->descripcion,      \PDO::PARAM_STR);
		$consulta->bindValue(':saldo',            $objEntidad->saldo,            \PDO::PARAM_INT);
		$consulta->bindValue(':tipoLiquidacion',  $objEntidad->tipoLiquidacion,  \PDO::PARAM_STR);
	
	}

}//class