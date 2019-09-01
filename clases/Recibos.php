<?php

require_once "AccesoDatos.php";

class Recibos
{

	//	Atributos
	public $id;
	public $idCtaCte;
	public $nroRecibo;
	public $codMedioPago;
	public $monto;


	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idCtaCte'   	 ,$objEntidad->idCtaCte         ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroRecibo'    ,$objEntidad->nroRecibo	    ,\PDO::PARAM_STR);
		$consulta->bindValue(':codMedioPago' ,$objEntidad->codMedioPago     ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'	     ,$objEntidad->monto            ,\PDO::PARAM_STR);
		
		return $consulta;
	}


}//class