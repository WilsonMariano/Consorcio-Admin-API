<?php

require_once "AccesoDatos.php";

class Usuarios
{

	//	Atributos
	public $id;
	public $email;
	public $password;


	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':email'	 ,$objEntidad->email    ,\PDO::PARAM_STR);
		$consulta->bindValue(':password' ,$objEntidad->password ,\PDO::PARAM_STR);
				
		return $consulta;
	}


	public static function GetAll(){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuarios");
		$consulta->execute();
		$arrResult= $consulta->fetchAll(PDO::FETCH_CLASS, "Usuarios");	
		return $arrResult;					
	}

	public static function Insert($usuario){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into usuarios (email, password) values(:email, :password)");

		self::setQueryParams($consulta,$usuario, false);

		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


}//class