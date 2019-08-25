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


	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id'					   ,$objEntidad->id                   ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionGlobal'    ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
		$consulta->bindValue(':idCtaCte'               ,$objEntidad->idCtaCte             ,\PDO::PARAM_INT);
		$consulta->bindValue(':coeficiente'            ,$objEntidad->coeficiente          ,\PDO::PARAM_STR);
		$consulta->bindValue(':interes'          	   ,$objEntidad->interes       	      ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'                  ,$objEntidad->monto 			      ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaRecalculo'         ,$objEntidad->fechaRecalculo       ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'                  ,$objEntidad->saldo       		  ,\PDO::PARAM_STR);
		
		return $consulta;
	}


}//class