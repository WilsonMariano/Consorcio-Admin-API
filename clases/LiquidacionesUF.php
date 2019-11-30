<?php

require_once "AccesoDatos.php";

class LiquidacionesUF
{
	// Atributos
	public $id;
	public $idLiquidacionGlobal;
	public $idCtaCte;
	public $idUF;
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
			$this->idUF = $arrData['idUF'];
			$this->coeficiente = $arrData['coeficiente'];
			$this->interes = $arrData['interes'] ?? null;
			$this->monto = $arrData['monto'] ?? null;
			$this->fechaRecalculo = $arrData['fechaRecalculo'] ?? null;
			$this->saldo = $arrData['saldo'] ?? null;
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionGlobal'    ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
		$consulta->bindValue(':idCtaCte'               ,$objEntidad->idCtaCte             ,\PDO::PARAM_INT);
		$consulta->bindValue(':idUF'                   ,$objEntidad->idUF                 ,\PDO::PARAM_INT);
		$consulta->bindValue(':coeficiente'            ,$objEntidad->coeficiente          ,\PDO::PARAM_STR);
		$consulta->bindValue(':interes'          	   ,$objEntidad->interes       	      ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'                  ,$objEntidad->monto 			      ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaRecalculo'         ,$objEntidad->fechaRecalculo       ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'                  ,$objEntidad->saldo       		  ,\PDO::PARAM_STR);
	}

	/**
	 * Inserta una LiquidacionUF, devolviendo el id asignado en la BD.
	 * Recibe por parámetro el objeto LiquidacionUF a persistir.
	 */
	public static function Insert($objEntidad){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("insert into LiquidacionesUF (idLiquidacionGlobal, idCtaCte, idUF, coeficiente, interes, monto, fechaRecalculo, saldo) 
			values (:idLiquidacionGlobal, :idCtaCte, :idUF, :coeficiente, :interes, :monto, :fechaRecalculo, :saldo)");
		$objEntidad->BindQueryParams($consulta, $objEntidad, false);	
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();		
	}

}//class