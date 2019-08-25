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
	public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id'             	,$objEntidad->id,             ,\PDO::PARAM_INT);
		$consulta->bindValue(':mtsCuadrados'   	,$objEntidad->mtsCuadrados,   ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   	,$objEntidad->tipoVivienda	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio'	,$objEntidad->nombreConsorcio ,\PDO::PARAM_STR);
		
		return $consulta;
	}


}//class