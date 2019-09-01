<?php

require_once "AccesoDatos.php";

class Diccionario
{

	//	Atributos
	public $id;
	public $codigo;
	public $valor;
 

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':codigo'	 ,$objEntidad->codigo    ,\PDO::PARAM_STR);
		$consulta->bindValue(':valor'	 ,$objEntidad->valor    ,\PDO::PARAM_STR);
 
		return $consulta;
	}


 


}//class