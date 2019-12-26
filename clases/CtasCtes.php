<?php

require_once "AccesoDatos.php";

class CtasCtes
{
	//Atributos
	public $id;
	public $idUF;
	public $idLiquidacion;
	public $fecha;
	public $descripcion;
	public $monto;
	public $saldo;

	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id            = $arrData["id"] ?? null;
			$this->idUF          = $arrData["idUF"];
			$this->idLiquidacion = $arrData["idLiquidacion"];
			$this->fecha         = $arrData["fecha"];
			$this->descripcion   = $arrData["descripcion"] ?? null;
			$this->monto         = $arrData["monto"];
			$this->saldo         = $arrData["saldo"];
		}
    }

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idUF'           ,$objEntidad->idUF           ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacion'  ,$objEntidad->idLiquidacion  ,\PDO::PARAM_INT);
		$consulta->bindValue(':fecha'          ,$objEntidad->fecha          ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion'    ,$objEntidad->descripcion    ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'          ,$objEntidad->monto          ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'          ,$objEntidad->saldo          ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve el ultimo saldo calculado para una CtaCte.
	 * Recibe por parámetro el id de la unidad funcional
	 */
	public static function GetLastSaldo($idUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("select saldo from CtasCtes where idUF = :idUF order by id desc limit 1");
		$consulta->bindValue(':idUF' , $idUF, \PDO::PARAM_INT);	
		$consulta->execute();

		return $consulta->fetch()[0];
	}

    /**
     * Genera un movimiento a favor del cliente, simulando una nota de crédito.
     */
	public static function NewCreditNote($uf, $monto){
		$ctaCte = new CtasCtes();
		$ctaCte->idUF = $uf->id;
		$ctaCte->fecha = date("Y-m-d");
		$ctaCte->descripcion = "NOTA DE CREDITO";
		$ctaCte->monto = $monto;
		$saldoActual = Helper::NumFormat(CtasCtes::GetLastSaldo($uf->nroUF) ?? 0);
		$ctaCte->saldo = $saldoActual + Helper::NumFormat($monto);

		$newId =  Funciones::InsertOne($ctaCte);
		if($newId < 1)
			throw new Exception("No se pudo actualizar uno de los movimientos en las cuentas corrientes.");
		else
			return $newId;
    }

}//class