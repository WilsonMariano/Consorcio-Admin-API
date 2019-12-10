<?php

require_once "AccesoDatos.php";

class Edificios{

	//Atributos
	public $id;
	public $nroManzana;
	public $nroEdificio;
	public $cantUF;
	
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->nroManzana = $arrData['nroManzana'];
			$this->nroEdificio = $arrData['nroEdificio'];
			$this->cantUF = $arrData['cantUF'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id', $objEntidad->id, \PDO::PARAM_INT);
		
		$consulta->bindValue(':nroManzana' , $objEntidad->nroManzana , \PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio', $objEntidad->nroEdificio, \PDO::PARAM_INT);
		$consulta->bindValue(':cantUF'     , $objEntidad->cantUF     , \PDO::PARAM_INT);
	}

	public static function GetOne($nroManzana, $nroEdificio){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from Edificios where nroManzana = :nroManzana and nroEdificio = :nroEdificio");
		$consulta->bindValue(':nroManzana' , $nroManzana, \PDO::PARAM_INT);	
		$consulta->bindValue(':nroEdificio' , $nroEdificio, \PDO::PARAM_INT);	
		$consulta->execute();
		$objEntidad= $consulta->fetchObject('Edificios');

		return $objEntidad;
	}

}//class