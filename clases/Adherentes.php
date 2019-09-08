<?php

require_once "AccesoDatos.php";

class Adherentes
{

	//	Atributos
	public $id;
	public $nombre;
	public $apellido;
	public $nroDocumento;
	public $telefono;
	public $email;
 	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':nombre'       ,$objEntidad->nombre        ,\PDO::PARAM_STR);
		$consulta->bindValue(':apellido'     ,$objEntidad->apellido      ,\PDO::PARAM_STR);
		$consulta->bindValue(':nroDocumento' ,$objEntidad->nroDocumento  ,\PDO::PARAM_INT);
		$consulta->bindValue(':telefono'     ,$objEntidad->telefono      ,\PDO::PARAM_STR);
		$consulta->bindValue(':email'        ,$objEntidad->email         ,\PDO::PARAM_STR);
		
		return $consulta;
	}

	

	public static function Insert($adherente){
		
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into adherentes values(:id,:nombre,:apellido,:nroDocumento,:telefono,:email)");
		self::setQueryParams($consulta,$adherente);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	
	}
	
		
}//class