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
			$consulta->bindValue(':id', $objEntidad->id, \PDO::PARAM_INT);
		
		$consulta->bindValue(':dia'   		,$objEntidad->dia         ,\PDO::PARAM_STR);
		$consulta->bindValue(':mes'   		,$objEntidad->mes         ,\PDO::PARAM_STR);
		$consulta->bindValue(':anio'		,$objEntidad->anio        ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipo'		,$objEntidad->tipo        ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion'	,$objEntidad->descripcion ,\PDO::PARAM_STR);
	}

	public static function IsInamovible($fecha){
		try {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objEntidad = new static();
		
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class .  
				"where dia = :dia and mes = :mes and tipo = '" . FeriadoTypeEnum::Inamovible ."'");
			$consulta->bindValue(':dia', $fecha->format("d") , PDO::PARAM_STR);
			$consulta->bindValue(':mes', $fecha->format("m") , PDO::PARAM_STR);
			$consulta->execute();
			$objEntidad = PDOHelper::FetchObject($consulta, static::class);
			
			return $consulta->rowCount() > 0 ? true : false;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $codigo, $e);		 
			throw new ErrorException("No se pudo recuperar el parámetro " . $codigo);
		}
	}

	public static function IsOptativo($fecha){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objEntidad = new static();

			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
				" where dia = :dia and mes = :mes and anio =:anio and tipo = '" . FeriadoTypeEnum::Optativo . "'");
			$consulta->bindValue(':dia', $fecha->format("d") , PDO::PARAM_STR);
			$consulta->bindValue(':mes', $fecha->format("m") , PDO::PARAM_STR);
			$consulta->bindValue(':anio', $fecha->format("Y") , PDO::PARAM_STR);
			$consulta->execute();
			$objEntidad = PDOHelper::FetchObject($consulta, static::class);
			
			return $consulta->rowCount() > 0 ? true : false;
		
		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $fecha, $e);		 
			throw new ErrorException("No se pudo recuperar el feriado " . $fecha);
		}
	}

	public static function IsHoliday($fecha){
        $fecha = DateTime::createFromFormat("Y-m-d", $fecha);
        return self::IsInamovible($fecha) || self::IsOptativo($fecha);
    }

}//class