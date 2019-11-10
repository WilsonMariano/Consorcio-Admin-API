<?php

require_once "AccesoDatos.php";

class GastosLiquidacionesUF{

	//Atributos
	public $id;
	public $idLiquidacionUF;
	public $idGastosLiquidaciones;
	public $monto;
	
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacionUF = $arrData['idLiquidacionUF'];
			$this->idGastosLiquidaciones = $arrData['idGastosLiquidaciones'];
			$this->monto = $arrData['monto'];
		}
	}

	//Configurar parámetros para las consultas
	public function BindQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id', $objEntidad->id, \PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionUF'        , $objEntidad->idLiquidacionUF       , \PDO::PARAM_INT);
		$consulta->bindValue(':idGastosLiquidaciones'  , $objEntidad->idGastosLiquidaciones , \PDO::PARAM_INT);
		$consulta->bindValue(':monto'		           , $objEntidad->monto                 , \PDO::PARAM_STR);
	}

}//class