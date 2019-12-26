<?php

require_once "AccesoDatos.php";

class Adherentes
{

	//Atributos
	public $id;
	public $nroAdherente;
	public $nombre;
	public $apellido;
	public $nroDocumento;
	public $telefono;
	public $email;
	 
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id           = $arrData["id"] ?? null;
			$this->nroAdherente = $arrData["nroAdherente"];
			$this->nombre       = $arrData["nombre"];
			$this->apellido     = $arrData["apellido"];
			$this->nroDocumento = $arrData["nroDocumento"];
			$this->telefono     = $arrData["telefono"] ?? null;
			$this->email        = $arrData["email"];
		}
    }

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':nroAdherente' ,$objEntidad->nroAdherente  ,\PDO::PARAM_INT);
		$consulta->bindValue(':nombre'       ,$objEntidad->nombre        ,\PDO::PARAM_STR);
		$consulta->bindValue(':apellido'     ,$objEntidad->apellido      ,\PDO::PARAM_STR);
		$consulta->bindValue(':nroDocumento' ,$objEntidad->nroDocumento  ,\PDO::PARAM_INT);
		$consulta->bindValue(':telefono'     ,$objEntidad->telefono      ,\PDO::PARAM_STR);
		$consulta->bindValue(':email'        ,$objEntidad->email         ,\PDO::PARAM_STR);
	}
		
}//class