<?php


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

	public static function GetByManzanaAndNumero($idManzana, $nroEdificio){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
				" where idManzana = :idManzana and nroEdificio = :nroEdificio");
			$consulta->bindValue(':idManzana' , $idManzana, \PDO::PARAM_INT);	
			$consulta->bindValue(':nroEdificio' , $nroEdificio, \PDO::PARAM_INT);	
			$consulta->execute();
			$objEntidad= PDOHelper::FetchObject($consulta, static::class);

			return $objEntidad;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $nroEdificio, $e);		 
			throw new ErrorException("No se pudo recuperar el edificio " . $nroEdificio);
		}
	}

 


}//class