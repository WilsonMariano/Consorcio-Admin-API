<?php

require_once "AccesoDatos.php";

class Usuarios
{

//	Atributos
public $id;
public $email;
public $password;

//	Configurar parámetros para las consultas
public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id',$objEntidad->id, \PDO::PARAM_INT);
		$consulta->bindValue(':email', $objEntidad->email, \PDO::PARAM_STR);
		$consulta->bindValue(':password', $objEntidad->password, \PDO::PARAM_STR);
		
		return $consulta;
}


}