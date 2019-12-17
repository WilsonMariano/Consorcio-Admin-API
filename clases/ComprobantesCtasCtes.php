<?php

require_once "AccesoDatos.php";

class ComprobantesCtasCtes
{

	//Atributos
	public $id;
	public $idCtaCte;
	public $idDetalleComprobante;
	public $nroRecibo;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id              = $arrData['id'] ?? null;
			$this->idCtaCte        = $arrData['idCtaCte'] ?? null;
			$this->idDetalleComprobante = $arrData['idDetalleComprobante'] ?? null;
			$this->nroRecibo       = $arrData['nroRecibo'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idCtaCte'   	          ,$objEntidad->idCtaCte              ,\PDO::PARAM_INT);
		$consulta->bindValue(':idDetalleComprobante'  ,$objEntidad->idDetalleComprobante  ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroRecibo'             ,$objEntidad->nroRecibo	          ,\PDO::PARAM_STR);
	}

}//class