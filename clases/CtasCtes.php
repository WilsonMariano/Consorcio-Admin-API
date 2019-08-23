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
public function setQueryParams($consulta,$obj){
		$consulta->bindValue(':id',          $obj->id,          \PDO::PARAM_INT);
		$consulta->bindValue(':idUF',        $obj->idUF,        \PDO::PARAM_INT);
		$consulta->bindValue(':fecha',       $obj->fecha,       \PDO::PARAM_STR);
		$consulta->bindValue(':descripcion', $obj->descripcion, \PDO::PARAM_STR);
		$consulta->bindValue(':monto',       $obj->monto,       \PDO::PARAM_STRSTR);
		$consulta->bindValue(':saldo',       $obj->saldo,       \PDO::PARAM_STRSTR);
		
		
		return $consulta;
}


}