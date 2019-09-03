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
		self::setQueryParams($consulta,$adherente, true);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	
	}

	
	// public static function GetWithPaged($rows,$page){
		// $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		// $consulta =$objetoAccesoDato->RetornarConsulta("call spGetAdherentesWithPaged($rows,$page,@o_total_pages)");
		// $consulta->execute();
		// $arrResult= $consulta->fetchAll(PDO::FETCH_CLASS, "Adherentes");	
		// $consulta->closeCursor();
		
		// $output = $objetoAccesoDato->Query("select @o_total_pages as total_pages")->fetchObject();
			
		// $result = new \stdClass();
		// $result->total_pages = $output->total_pages;
		// $result->data = $arrResult;
		
 		// return $result;					
		
	// }


}//class