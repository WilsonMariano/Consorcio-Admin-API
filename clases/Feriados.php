<?php


class Feriados{

	//	Atributos
	public $id;
	public $dia;
	public $mes;
	public $anio;
	public $tipo;
	public $descripcion;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ;
			$this->dia = $arrData['dia'];
			$this->mes = $arrData['mes'] ;
			$this->anio = $arrData['anio'] ?? null;
			$this->anio = $arrData['tipo'] ;
			$this->anio = $arrData['descripcion'] ?? null;
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':dia'   	,$objEntidad->dia    ,\PDO::PARAM_STR);
		$consulta->bindValue(':mes'   	,$objEntidad->mes	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':anio'	,$objEntidad->anio ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipo'	,$objEntidad->tipo ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion'	,$objEntidad->descripcion ,\PDO::PARAM_STR);
	}

	public static function IsInamovible($fecha){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new static();
	
		$dia = $fecha->format("d");
        $mes = $fecha->format("m");
 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class .  
			"where dia = :dia and mes = :mes and tipo = '" . FeriadoTypeEnum::Inamovible ."'");
		$consulta->bindValue(':dia', $dia , PDO::PARAM_STR);
		$consulta->bindValue(':mes', $mes , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad = PDOHelper::FetchObject($consulta, static::class);
		
		return $consulta->rowCount() > 0 ? true : false;
	}

	public static function IsOptativo($fecha){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new static();
		
		$dia = $fecha->format("d");
        $mes = $fecha->format("m");
		$anio = $fecha->format("Y");

		$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
			" where dia = :dia and mes = :mes and anio =:anio and tipo = '" . FeriadoTypeEnum::Optativo . "'");
		$consulta->bindValue(':dia', $dia , PDO::PARAM_STR);
		$consulta->bindValue(':mes', $mes , PDO::PARAM_STR);
		$consulta->bindValue(':anio', $anio , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad = PDOHelper::FetchObject($consulta, static::class);
		
		return $consulta->rowCount() > 0 ? true : false;
	}

}//class