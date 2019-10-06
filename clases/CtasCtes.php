<?php

require_once "AccesoDatos.php";

class CtasCtes
{

	//Atributos
	public $id;
	public $idUF;
	public $fecha;
	public $descripcion;
	public $monto;
	public $saldo;

	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"];
			$this->idUF = $arrData["idUF"];
			$this->fecha = $arrData["fecha"];
			$this->descripcion = $arrData["descripcion"];
			$this->monto = $arrData["monto"];
			$this->saldo = $arrData["saldo"];
		}
    }

	//Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idUF'        ,$objEntidad->idUF         ,\PDO::PARAM_INT);
		$consulta->bindValue(':fecha'       ,$objEntidad->fecha        ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion' ,$objEntidad->descripcion  ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'       ,$objEntidad->monto        ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'       ,$objEntidad->saldo        ,\PDO::PARAM_STR);
	}

}//class