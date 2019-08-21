<?php

require_once "AccesoDatos.php";

class UF
{
	public $id;
	public $idManzana;
	public $idAdherente;
	public $nroEdificio;
	public $departamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;
 
 	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$obj){
		$consulta->bindValue(':id'           , $obj->id,            \PDO::PARAM_INT);
		$consulta->bindValue(':idManzana'    , $obj->idManzana,     \PDO::PARAM_INT);
		$consulta->bindValue(':idAdherente'  , $obj->idAdherente,   \PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio'  , $obj->nroEdificio,   \PDO::PARAM_INT);
		$consulta->bindValue(':departamento' , $obj->departamento,  \PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  , $obj->codSitLegal,   \PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  , $obj->coeficiente,   \PDO::PARAM_INT);
		$consulta->bindValue(':codAlquila'   , $obj->codAlquila,    \PDO::PARAM_STR);
		
		return $consulta;
	}





}