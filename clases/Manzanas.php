<?php

require_once "AccesoDatos.php";

class Manzanas
{

//	Atributos
public $id;
public $mtsCuadrados;
public $tipoVivienda;
public $nombreConsorcio;

//	Configurar parámetros para las consultas
public function setQueryParams($consulta,$obj){
		$consulta->bindValue(':id'             ,$obj->id,             \PDO::PARAM_INT);
		$consulta->bindValue(':mtsCuadrados'   ,$obj->mtsCuadrados,   \PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   ,$obj->tipoVivienda,   \PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio',$obj->nombreConsorcio,\PDO::PARAM_STR);
		
		return $consulta;
}


}