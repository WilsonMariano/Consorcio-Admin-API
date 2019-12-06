<?php

require_once "AccesoDatos.php";

class UF{
	
	//	Atributos
	public $id;
	public $nroManzana;
	public $nroAdherente;
	public $nroUF;
	public $nroEdificio;
	public $codDepartamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->nroManzana = $arrData['nroManzana'];
			$this->nroAdherente = $arrData['nroAdherente'];
			$this->nroUF = $arrData['nroUF'] ?? null;
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
		
		$consulta->bindValue(':nroManzana'    	,$objEntidad->nroManzana       ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroAdherente'  	,$objEntidad->nroAdherente     ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroUF'  	        ,$objEntidad->nroUF           ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio'  	,$objEntidad->nroEdificio     ,\PDO::PARAM_INT);
		$consulta->bindValue(':codDepartamento' ,$objEntidad->codDepartamento ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  	,$objEntidad->codSitLegal     ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  	,$objEntidad->coeficiente     ,\PDO::PARAM_STR);
		$consulta->bindValue(':codAlquila'   	,$objEntidad->codAlquila      ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de manzana a la cual pertenecen.
	 * Recibe por parámetro un nroManzana (de la tabla Manzanas)
	 */
	public static function GetByManzana($nroManzana){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where nroManzana =:nroManzana");
		$consulta->bindValue(':nroManzana', $nroManzana , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de edificio al cual pertenecen.
	 * Recibe por parámetro un número de edificio que se preasume válido.
	 */
	public static function GetByEdificio($nroEdificio){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where nroEdificio =:nroEdificio");
		$consulta->bindValue(':nroEdificio', $nroEdificio , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

	/**
	 * Devuelve un objeto UF buscando por el campo nroUF
	 */
	public static function GetByNumero($nroUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where nroUF =:nroUF");
		$consulta->bindValue(':nroUF', $nroUF , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetch(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

	public static function IsDuplicated($nroUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where nroUF =:nroUF");
		$consulta->bindValue(':nroUF', $nroUF, PDO::PARAM_INT);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

}//class
