<?php

require_once "AccesoDatos.php";

class GastosLiquidacionesUF
{

	//	Atributos
	public $id;
	public $idLiquidacionUF;
	public $idGastosLiquidaciones;
	public $monto;
	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id'					,$objEntidad->id                 ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionUF'		,$objEntidad->idLiquidacionUF    ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacionUF'		,$objEntidad->idLiquidacionUF    ,\PDO::PARAM_INT);
		$consulta->bindValue(':monto'		        ,$objEntidad->monto              ,\PDO::PARAM_STR);
		
		return $consulta;
	}


}//class