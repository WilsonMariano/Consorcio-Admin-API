<?php

require_once "AccesoDatos.php";

class entidad
{

	public $idUsuario;
	public $nombre;
	public $apellido;
	public $dni;
 	public $usuario;
 	public $password;
 	public $domicilio;
 	public $tipoUsuario;


 	public static function Login($usuario, $password) 
	{	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from tbUsuarios where usuario = :usuario AND password = :password");
		$consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);
		$consulta->execute();
		$usuarioBuscado= $consulta->fetchObject('usuario');
		return $usuarioBuscado;						
	}

	public static function Insertar($usuario)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into tbUsuarios (nombre, apellido, dni, usuario, password, domicilio, tipoUsuario) 
			values(:nombre, :apellido, :dni, :usuario, :password, :domicilio, :tipoUsuario)");

		$consulta->bindValue(':nombre',$usuario->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':apellido',$usuario->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':dni', $usuario->dni, PDO::PARAM_STR);
		$consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
		$consulta->bindValue(':password', $usuario->password, PDO::PARAM_STR);
		$consulta->bindValue(':domicilio', $usuario->domicilio, PDO::PARAM_STR);
		$consulta->bindValue(':tipoUsuario', $usuario->tipoUsuario, PDO::PARAM_STR);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public static function Editar($usuario)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
		UPDATE tbUsuarios SET
			nombre = :nombre, 
			apellido = :apellido, 
			dni = :dni, 
			usuario = :usuario, 
			password = :password, 
			domicilio = :domicilio,  
			tipoUsuario = :tipoUsuario
		WHERE idUsuario = :idUsuario");
		$consulta->bindValue(':idUsuario',$usuario->idUsuario, PDO::PARAM_INT);
		$consulta->bindValue(':nombre',$usuario->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':apellido',$usuario->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':dni', $usuario->dni, PDO::PARAM_STR);
		$consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
		$consulta->bindValue(':password', $usuario->password, PDO::PARAM_STR);
		$consulta->bindValue(':domicilio', $usuario->domicilio, PDO::PARAM_STR);
		$consulta->bindValue(':tipoUsuario', $usuario->tipoUsuario, PDO::PARAM_STR);

		$consulta->execute();
		return $consulta->rowCount();
	}


}