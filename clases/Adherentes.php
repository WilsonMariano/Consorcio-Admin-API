<?php

require_once "AccesoDatos.php";

class Adherentes
{

	//	Atributos
	public $id;
	public $nombre;
	public $apellido;
	public $dni;
	public $telefono;
	public $email;
 	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad){
		$consulta->bindValue(':id'       ,$objEntidad->id        ,\PDO::PARAM_INT);
		$consulta->bindValue(':nombre'   ,$objEntidad->nombre    ,\PDO::PARAM_STR);
		$consulta->bindValue(':apellido' ,$objEntidad->apellido  ,\PDO::PARAM_STR);
		$consulta->bindValue(':dni'      ,$objEntidad->dni       ,\PDO::PARAM_INT);
		$consulta->bindValue(':telefono' ,$objEntidad->telefono  ,\PDO::PARAM_STR);
		$consulta->bindValue(':email'    ,$objEntidad->email     ,\PDO::PARAM_STR);
		
		return $consulta;
	}


	public static function GetWithPaged(){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuarios");
		$consulta->execute();
		$arrResult= $consulta->fetchAll(PDO::FETCH_CLASS, "Usuarios");	
		return $arrResult;					
	}


}//class