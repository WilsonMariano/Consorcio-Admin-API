<?php

require_once "AccesoDatos.php";

class CtasCtes
{

	//	Atributos
	public $id;
	public $idUF;
	public $fecha;
	public $descripcion;
	public $monto;
	public $saldo;

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idUF'        ,$objEntidad->idUF         ,\PDO::PARAM_INT);
		$consulta->bindValue(':fecha'       ,$objEntidad->fecha        ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion' ,$objEntidad->descripcion  ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'       ,$objEntidad->monto        ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'       ,$objEntidad->saldo        ,\PDO::PARAM_STR);
	}


}