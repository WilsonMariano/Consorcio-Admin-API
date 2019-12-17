<?php

require_once "AccesoDatos.php";

class DetallesComprobantes
{

	//Atributos
	public $id;
	public $idComprobanteCtaCte;
	public $codMedioPago;
	public $monto;
	public $descripcion;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id                   = $arrData['id'] ?? null;
			$this->idComprobanteCtaCte  = $arrData['idComprobanteCtaCte'] ?? null;
			$this->codMedioPago         = $arrData['codMedioPago'] ?? null;
			$this->monto                = $arrData['monto'] ?? null;
			$this->descripcion          = $arrData['descripcion'];			
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idComprobanteCtaCte'  ,$objEntidad->idComprobanteCtaCte  ,\PDO::PARAM_INT);
		$consulta->bindValue(':descripcion'          ,$objEntidad->descripcion	        ,\PDO::PARAM_STR);
		$consulta->bindValue(':codMedioPago'         ,$objEntidad->codMedioPago         ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'	             ,$objEntidad->monto                ,\PDO::PARAM_STR);
	}

}//class