<?php


class Liquidaciones
{
	// Atributos
	public $id;
	public $idUF;
	public $interesAcumulado;
	public $saldoInteres;
	public $monto;
	public $saldoMonto;
	public $fechaRecalculo;
	public $fechaEmision;
	public $tasaInteres;

	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id               = $arrData['id'] ?? null;
			$this->idUF             = $arrData['idUF'];
			$this->interesAcumulado = $arrData['interesAcumulado'] ?? 0;
			$this->saldoInteres     = $arrData['saldoInteres'] ?? 0;
			$this->monto            = $arrData['monto'] ?? null;
			$this->saldoMonto       = $arrData['saldoMonto'] ?? null;
			$this->fechaRecalculo   = $arrData['fechaRecalculo'] ?? null;
			$this->fechaEmision     = $arrData['fechaEmision'] ?? date("Y-m-d");
			$this->tasaInteres      = $arrData['tasaInteres'] ?? 0;
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idUF'              ,$objEntidad->idUF              ,\PDO::PARAM_INT);
		$consulta->bindValue(':interesAcumulado'  ,$objEntidad->interesAcumulado  ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldoInteres'      ,$objEntidad->saldoInteres      ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'             ,$objEntidad->monto 			  ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldoMonto'        ,$objEntidad->saldoMonto        ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaRecalculo'    ,$objEntidad->fechaRecalculo    ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaEmision'      ,$objEntidad->fechaEmision      ,\PDO::PARAM_STR);
		$consulta->bindValue(':tasaInteres'       ,$objEntidad->tasaInteres       ,\PDO::PARAM_STR);
	}

	/**
	 * Actualiza los saldos de una liquidación.
	 */
	public function UpdateSaldos($idLiquidacion, $parMonto){
		$liq = Funciones::GetOne($idLiquidacion, static::Class);
		$monto = NumHelper::NumFormat($parMonto);
		$saldoMontoAux = $monto;

		if(NumHelper::IsNegative($liq->saldoInteres)){
			$saldoMontoAux = $monto + NumHelper::NumFormat($liq->saldoInteres);	
			if($saldoMontoAux >= 0){
				$liq->saldoInteres = 0;
			}else{
				$liq->saldoInteres += $monto;
			}
		}

		if(NumHelper::IsNegative($liq->saldoMonto) && $saldoMontoAux > 0){
			$liq->saldoMonto += $saldoMontoAux;
			if($liq->saldoMonto > 0)
				$liq->saldoMonto = 0;
		}

		return Funciones::UpdateOne($liq);
	}

}//class