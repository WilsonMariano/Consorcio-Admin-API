<?php

require_once "AccesoDatos.php";

class GastosLiquidaciones
{

	//	Atributos
	public $id;
	public $idLiquidacionGlobal;
	public $idConcepto;
	public $monto;
	public $detalle;
	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id'					 ,$objEntidad->id                   ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionGlobal'	 ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
		$consulta->bindValue(':idConcepto'		     ,$objEntidad->idConcepto           ,\PDO::PARAM_INT);
		$consulta->bindValue(':monto'		         ,$objEntidad->monto                ,\PDO::PARAM_STR);
		$consulta->bindValue(':detalle'		         ,$objEntidad->detalle              ,\PDO::PARAM_STR);
		
		return $consulta;
	}


}//class