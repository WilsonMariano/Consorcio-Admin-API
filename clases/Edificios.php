<?php

require_once "AccesoDatos.php";

class Edificios{

	//Atributos
	public $id;
	public $idManzana;
	public $nroEdificio;
	public $cantUF;
	
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idManzana = $arrData['idManzana'];
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
		
		$consulta->bindValue(':idManzana' , $objEntidad->idManzana , \PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio', $objEntidad->nroEdificio, \PDO::PARAM_INT);
		$consulta->bindValue(':cantUF'     , $objEntidad->cantUF     , \PDO::PARAM_INT);
	}

	public static function GetOne($idManzana, $nroEdificio){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from Edificios where idManzana = :idManzana and nroEdificio = :nroEdificio");
		$consulta->bindValue(':idManzana' , $idManzana, \PDO::PARAM_INT);	
		$consulta->bindValue(':nroEdificio' , $nroEdificio, \PDO::PARAM_INT);	
		$consulta->execute();
		$objEntidad= $consulta->fetchObject('Edificios');

		return $objEntidad;
	}

}//class