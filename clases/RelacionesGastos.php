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
		$this->idGastosLiquidaciones = $arrData["idGastosLiquidaciones"] ?? null;
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

	public static function Exists($idGastosLiquidaciones){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from RelacionesGastos where idGastosLiquidaciones = :idGastosLiquidaciones");	
        $consulta->bindValue(':idGastosLiquidaciones',$idGastosLiquidaciones, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

	//Elimino todas las relaciones pertenicientes a un mismo GastoLiquidacion
	public static function DeleteAll($idGastosLiquidaciones){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from RelacionesGastos where idGastosLiquidaciones = :idGastosLiquidaciones");	
        $consulta->bindValue(':idGastosLiquidaciones',$idGastosLiquidaciones, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

}//class