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
	public $interesAcumulado;
	public $saldoInteres;
	public $monto;
	public $saldoMonto;
	public $fechaRecalculo;

	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacionGlobal = $arrData['idLiquidacionGlobal'];
			$this->idCtaCte = $arrData['idCtaCte'];
			$this->idUF = $arrData['idUF'];
			$this->coeficiente = $arrData['coeficiente'];
			$this->interesAcumulado = $arrData['interesAcumulado'] ?? 0;
			$this->saldoInteres = $arrData['saldoInteres'] ?? 0;
			$this->monto = $arrData['monto'] ?? null;
			$this->saldoMonto = $arrData['saldoMonto'] ?? null;
			$this->fechaRecalculo = $arrData['fechaRecalculo'] ?? null;
		} else {
			$this->interesAcumulado = 0;
			$this->saldoInteres = 0;
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
		$consulta->bindValue(':interesAcumulado'   	   ,$objEntidad->interesAcumulado     ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldoInteres'           ,$objEntidad->saldoInteres         ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'                  ,$objEntidad->monto 			      ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldoMonto'             ,$objEntidad->saldoMonto       	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaRecalculo'         ,$objEntidad->fechaRecalculo       ,\PDO::PARAM_STR);
	}

	/**
	 * Inserta una LiquidacionUF, devolviendo el id asignado en la BD.
	 * Recibe por parámetro el objeto LiquidacionUF a persistir.
	 */
	public static function Insert($objEntidad){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta(
			"insert into LiquidacionesUF (idLiquidacionGlobal, idCtaCte, idUF, coeficiente, interesAcumulado, saldoInteres, monto, saldoMonto, fechaRecalculo) 
			values (:idLiquidacionGlobal, :idCtaCte, :idUF, :coeficiente, :interesAcumulado, :saldoInteres, :monto, :saldoMonto, :fechaRecalculo)");
		$objEntidad->BindQueryParams($consulta, $objEntidad, false);	
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();		
	}

}//class