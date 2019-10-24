<?php

require_once "AccesoDatos.php";

class Comprobantes
{

	//Atributos
	public $id;
	public $idCtaCte;
	public $idLiquidacionUf;
	public $numero;
	public $codMedioPago;
	public $monto;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id              = $arrData['id'] ?? null;
			$this->idCtaCte        = $arrData['idCtaCte'] ?? null;
			$this->idLiquidacionUf = $arrData['idLiquidacionUf'] ?? null;
			$this->numero       = $arrData['numero'];
			$this->codMedioPago    = $arrData['codMedioPago'] ?? null;
			$this->monto           = $arrData['monto'] ?? null;
		}
	}

	//Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idCtaCte'   	     ,$objEntidad->idCtaCte         ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionUf'  ,$objEntidad->idLiquidacionUf  ,\PDO::PARAM_INT);
		$consulta->bindValue(':numero'        ,$objEntidad->numero	    ,\PDO::PARAM_STR);
		$consulta->bindValue(':codMedioPago'     ,$objEntidad->codMedioPago     ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'	         ,$objEntidad->monto            ,\PDO::PARAM_STR);
	}

}//class