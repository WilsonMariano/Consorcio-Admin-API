<?php

require_once "AccesoDatos.php";

class RelacionesGastos
{

	//	Atributos
	public $id;
	public $idGastosLiquidaciones;
	public $entidad;
	public $numero;


	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idGastosLiquidaciones' ,$objEntidad->idGastosLiquidaciones ,\PDO::PARAM_INT);
		$consulta->bindValue(':entidad'   	          ,$objEntidad->entidad	              ,\PDO::PARAM_STR);
		$consulta->bindValue(':numero'	              ,$objEntidad->entidad               ,\PDO::PARAM_INT);
		
		return $consulta;
	}


}//class