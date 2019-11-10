<?php

require_once "AccesoDatos.php";

class LiquidacionesUF
{
	//	Atributos
	public $id;
	public $idLiquidacionGlobal;
	public $idCtaCte;
	public $coeficiente;
	public $interes;
	public $monto;
	public $fechaRecalculo;
	public $saldo;

	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacionGlobal = $arrData['idLiquidacionGlobal'];
			$this->idCtaCte = $arrData['idCtaCte'];
			$this->coeficiente = $arrData['coeficiente'];
			$this->interes = $arrData['interes'] ?? null;
			$this->monto = $arrData['monto'];
			$this->fechaRecalculo = $arrData['fechaRecalculo'] ?? null;
			$this->saldo = $arrData['saldo'];
		}
	}

	//	Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionGlobal'    ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
		$consulta->bindValue(':idCtaCte'               ,$objEntidad->idCtaCte             ,\PDO::PARAM_INT);
		$consulta->bindValue(':coeficiente'            ,$objEntidad->coeficiente          ,\PDO::PARAM_STR);
		$consulta->bindValue(':interes'          	   ,$objEntidad->interes       	      ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'                  ,$objEntidad->monto 			      ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaRecalculo'         ,$objEntidad->fechaRecalculo       ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'                  ,$objEntidad->saldo       		  ,\PDO::PARAM_STR);
	}

}//class