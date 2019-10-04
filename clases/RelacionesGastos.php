<?php

require_once "AccesoDatos.php";

class RelacionesGastos
{

	//	Atributos
	public $id;
	public $idGastosLiquidaciones;
	public $entidad;
	public $numero;

	// Constructor customizado
	function __construct($arrData){
		$this->id = $arrData["id"];
		$this->idGastosLiquidaciones = $arrData["idGastosLiquidaciones"];
		$this->entidad = $arrData["entidad"];
		$this->numero = $arrData["numero"];
	}

	public static function Insert($relacionGasto){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into relacionesgastos (idGastosLiquidaciones, entidad, numero)
			 values(:idGastosLiquidaciones, :entidad, :numero)");
		self::setQueryParams($consulta,$relacionGasto,false);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idGastosLiquidaciones' ,$objEntidad->idGastosLiquidaciones ,\PDO::PARAM_INT);
		$consulta->bindValue(':entidad'   	          ,$objEntidad->entidad	              ,\PDO::PARAM_STR);
		$consulta->bindValue(':numero'	              ,$objEntidad->entidad               ,\PDO::PARAM_INT);
	}


}//class