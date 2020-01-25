<?php


class Diccionario{

	//Atributos
	public $id;
	public $codigo;
	public $valor;
 
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"] ?? null;
			$this->codigo = $arrData["codigo"];
			$this->valor = $arrData["valor"];
		}
	}
	
	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':codigo'	 ,$objEntidad->codigo    ,\PDO::PARAM_STR);
		$consulta->bindValue(':valor'	 ,$objEntidad->valor    ,\PDO::PARAM_STR);
	}

 	public static function GetAll($codigo){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
			" where codigo like '" . $codigo . "%'");
		$consulta->execute();		
		$arrObjEntidad = PDOHelper::FetchAll($consulta);	
		
		return $arrObjEntidad;
	}

	public static function GetValue($codigo){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new static();
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from ". static::class . " where codigo =:codigo");
		$consulta->bindValue(':codigo', $codigo , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad = PDOHelper::FetchObject($consulta, static::class);
		
		return $objEntidad->valor;						
	}

}//class