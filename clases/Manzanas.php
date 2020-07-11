<?php

require_once __DIR__ . '\helpers\NumHelper.php';
require_once __DIR__ . '\enums\LiquidacionTypeEnum.php';

class Manzanas{

	//	Atributos
	public $id;
	public $nroManzana;
	public $mtsCuadrados;
	public $tipoVivienda;
	public $nombreConsorcio;
	public $montoFondoReserva;
	public $montoFondoPrevision;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idnroManzana = $arrData['nroManzana'];
			$this->mtsCuadrados = $arrData['mtsCuadrados'];
			$this->tipoVivienda = $arrData['tipoVivienda'] ?? null;
			$this->nombreConsorcio = $arrData['nombreConsorcio'];
			$this->montoFondoReserva = $arrData['montoFondoReserva'];
			$this->montoFondoPrevision = $arrData['montoFondoPrevision'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':nroManzana'   	    ,$objEntidad->nroManzana          ,\PDO::PARAM_INT);
		$consulta->bindValue(':mtsCuadrados'   	    ,$objEntidad->mtsCuadrados        ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   	    ,$objEntidad->tipoVivienda	      ,\PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio'	    ,$objEntidad->nombreConsorcio     ,\PDO::PARAM_STR);
		$consulta->bindValue(':montoFondoReserva'	,$objEntidad->montoFondoReserva   ,\PDO::PARAM_STR);
		$consulta->bindValue(':montoFondoPrevision'	,$objEntidad->montoFondoPrevision ,\PDO::PARAM_STR);
	}

	public static function GetByNumero($nroManzana){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from " . static::class . " where nroManzana= :nroManzana");
			$consulta->bindValue(':nroManzana' , $nroManzana, \PDO::PARAM_INT);	
			$consulta->execute();
			$objEntidad= PDOHelper::FetchObject($consulta, static::class);

			return $objEntidad;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $nroManzana, $e);		 
			throw new ErrorException("No se pudo recuperar la manzana numero" . $nroManzana);
		}
	}

	/**
	 * Devuelve el monto a cobrar de un fondo especial que se encuentra parametrizado para un idManzana especifico.
	 */
	public static function GetMontoFondoEspecial($idManzana, $tipoLiquidacion){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$campoMonto = $tipoLiquidacion == LiquidacionTypeEnum::FondoReserva ? "montoFondoReserva" : "montoFondoPrevision";

			$consulta = $objetoAccesoDato->RetornarConsulta("select " . $campoMonto . " as monto from " . static::class . " where id = :idManzana");
			$consulta->bindValue(':idManzana' , $idManzana, \PDO::PARAM_INT);	
			$consulta->execute();
			$objEntidad= PDOHelper::FetchObject($consulta, static::class);

			return $objEntidad->monto;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idManzana, $e);		 
			throw new ErrorException("No se pudo recuperar el monto del fondo especial para la manzana de id " . $idManzana);
		}
	}

}//class