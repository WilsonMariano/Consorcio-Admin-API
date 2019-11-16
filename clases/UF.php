<?php

require_once "AccesoDatos.php";

class UF{
	
	//	Atributos
	public $id;
	public $idManzana;
	public $idAdherente;
	public $nroEdificio;
	public $codDepartamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idManzana = $arrData['idManzana'];
			$this->idAdherente = $arrData['idAdherente'];
			$this->nroEdificio = $arrData['nroEdificio'] ?? null;
			$this->codDepartamento = $arrData['codDepartamento'] ?? null;
			$this->codSitLegal = $arrData['codSitLegal'];
			$this->coeficiente = $arrData['coeficiente'];
			$this->codAlquila = $arrData['codAlquila'];
		}
	}

	/**
	* Bindeo los parametros para la consulta SQL.
	*/
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idManzana'    	,$objEntidad->idManzana       ,\PDO::PARAM_INT);
		$consulta->bindValue(':idAdherente'  	,$objEntidad->idAdherente     ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio'  	,$objEntidad->nroEdificio     ,\PDO::PARAM_INT);
		$consulta->bindValue(':codDepartamento' ,$objEntidad->codDepartamento ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  	,$objEntidad->codSitLegal     ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  	,$objEntidad->coeficiente     ,\PDO::PARAM_STR);
		$consulta->bindValue(':codAlquila'   	,$objEntidad->codAlquila      ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un edificio si existe en la BD.
	 * Recibe por parámetro el número del mismo.
	 */
	public static function ValidateBuilding ($nroEdificio){
    	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new UF();
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where nroEdificio =:nroEdificio");
		$consulta->bindValue(':nroEdificio', $nroEdificio , PDO::PARAM_INT);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject(PDO::FETCH_ASSOC);
		
		return $objEntidad;						
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de manzana a la cual pertenecen.
	 * Recibe por parámetro un idManzana (de la tabla Manzanas)
	 */
	public static function GetByManzana($idManzana){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where idManzana =:idManzana");
		$consulta->bindValue(':idManzana', $idManzana , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

}//class