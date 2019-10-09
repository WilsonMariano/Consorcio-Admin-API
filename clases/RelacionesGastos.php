<?php

require_once "AccesoDatos.php";

class RelacionesGastos{

	//Atributos
	public $id;
	public $idGastosLiquidaciones;
	public $entidad;
	public $numero;

	//Constructor customizado
	public function __construct($arrData){
		$this->id = $arrData["id"] ?? null;
		$this->idGastosLiquidaciones = $arrData["idGastosLiquidaciones"];
		$this->entidad = $arrData["entidad"] ?? null;
		$this->numero = $arrData["numero"] ?? null;
	}

	//Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idGastosLiquidaciones' ,$objEntidad->idGastosLiquidaciones ,\PDO::PARAM_INT);
		$consulta->bindValue(':entidad'   	          ,$objEntidad->entidad	              ,\PDO::PARAM_STR);
		$consulta->bindValue(':numero'	              ,$objEntidad->numero                ,\PDO::PARAM_INT);
	}

}//class