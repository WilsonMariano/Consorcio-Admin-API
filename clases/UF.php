<?php

require_once "AccesoDatos.php";

class UF
{
	//	Atributos
	public $id;
	public $idManzana;
	public $idAdherente;
	public $nroEdificio;
	public $departamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;
 
 	
	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id'           ,$objEntidad->id            ,\PDO::PARAM_INT);
		$consulta->bindValue(':idManzana'    ,$objEntidad->idManzana     ,\PDO::PARAM_INT);
		$consulta->bindValue(':idAdherente'  ,$objEntidad->idAdherente   ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio'  ,$objEntidad->nroEdificio   ,\PDO::PARAM_INT);
		$consulta->bindValue(':departamento' ,$objEntidad->departamento  ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  ,$objEntidad->codSitLegal   ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  ,$objEntidad->coeficiente   ,\PDO::PARAM_INT);
		$consulta->bindValue(':codAlquila'   ,$objEntidad->codAlquila    ,\PDO::PARAM_STR);
		
		return $consulta;
	}


}//class