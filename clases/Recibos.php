<?php

require_once "AccesoDatos.php";

class Recibos
{

	//Atributos
	public $id;
	public $idCtaCte;
	public $nroRecibo;
	public $codMedioPago;
	public $monto;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idCtaCte = $arrData['idCtaCte'] ?? null;
			$this->nroRecibo = $arrData['nroRecibo'];
			$this->codMedioPago = $arrData['codMedioPago'] ?? null;
			$this->monto = $arrData['monto'] ?? null;
		}
	}

	//Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idCtaCte'   	 ,$objEntidad->idCtaCte         ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroRecibo'    ,$objEntidad->nroRecibo	    ,\PDO::PARAM_STR);
		$consulta->bindValue(':codMedioPago' ,$objEntidad->codMedioPago     ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'	     ,$objEntidad->monto            ,\PDO::PARAM_STR);
	}

}//class