<?php

require_once "AccesoDatos.php";

class Usuarios
{

//	Atributos
public $id;
public $email;
public $password;

//	Configurar parámetros para las consultas
public function setQueryParams($consulta,$obj){
		$consulta->bindValue(':id',$obj->id, \PDO::PARAM_INT);
		$consulta->bindValue(':email', $obj->email, \PDO::PARAM_STR);
		$consulta->bindValue(':password', $obj->password, \PDO::PARAM_STR);
		
		return $consulta;
}


}