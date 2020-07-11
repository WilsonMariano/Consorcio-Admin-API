<?php


class ConceptosGastos
{

	//Atributos
	public $id;
	public $codigo;
	public $conceptoGasto;
 
	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id            = $arrData["id"] ?? null;
			$this->codigo        = $arrData["codigo"];
			$this->conceptoGasto = $arrData["conceptoGasto"];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':codigo'	       ,$objEntidad->codigo         ,\PDO::PARAM_STR);
		$consulta->bindValue(':conceptoGasto'  ,$objEntidad->conceptoGasto  ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un objeto ConceptoGasto , buscando por el campo código.
	 * Recibe por parámetro el código a buscar.
	 */
	public static function GetByCodigo($codigo){	
		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objEntidad = new static();
			
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class . " where codigo =:codigo");
			$consulta->bindValue(':codigo', $codigo , PDO::PARAM_STR);
			$consulta->execute();
			$objEntidad= PDOHelper::FetchObject($consulta, static::class);
			
			return $objEntidad;						

		}catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $objEntidad , $e);		 
			throw new ErrorException("No se pudo recuperar una entidad del tipo " . static::class);
		}
	}

}//class