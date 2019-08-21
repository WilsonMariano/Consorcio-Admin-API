<?php

require_once "AccesoDatos.php";

class Adherentes
{

	public $id;
	public $nombre;
	public $apellido;
	public $dni;
	public $telefono;
	public $email;
 	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$obj){
		$consulta->bindValue(':id'      , $obj->id,       \PDO::PARAM_INT);
		$consulta->bindValue(':nombre'  , $obj->nombre,   \PDO::PARAM_STR);
		$consulta->bindValue(':apellido', $obj->apellido, \PDO::PARAM_STR);
		$consulta->bindValue(':dni'     , $obj->dni,      \PDO::PARAM_INT);
		$consulta->bindValue(':telefono', $obj->telefono, \PDO::PARAM_STR);
		$consulta->bindValue(':email'   , $obj->email,    \PDO::PARAM_STR);
		
		return $consulta;
	}





}