<?php

require_once __DIR__ . '\helpers\SimpleTypesHelper.php';
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

	/**
	 * Calcula el porcentaje de gasto que le corresponde a cada una de las manzanas recibidas. Devuelve un array con la estructura [nroManzana] = [coeficiente].
	 * Recibe por parámetro un array con los nroManzana para los cuales calculará el coeficiente.
	 */
	public static function GetPorcentajes($arrManzanas){
		try{
			//Traigo todas las manzanas
			$manzanas = Funciones::GetAll(static::class);

			if($manzanas){
				$totalMts = 0;
				$result = new \stdClass();
				// Burbujeo para armar el result(preliminar) y tambien calcular el total de mts cuadrados entre todas las manzanas recibidas por param
				foreach ($arrManzanas as $nroManzana) {
					foreach ($manzanas as $manzana) {
						if($nroManzana == $manzana->nroManzana) {
							$result->$nroManzana = SimpleTypesHelper::NumFormat($manzana->mtsCuadrados);
							$totalMts += SimpleTypesHelper::NumFormat($manzana->mtsCuadrados);
							break;
						}
					}	
				}

				// Itero para calcular el coeficiente de cada manzana y actualizar el result final.
				foreach ($arrManzanas as $nroManzana) {
					$valor =  (SimpleTypesHelper::NumFormat($result->$nroManzana) * 100) / SimpleTypesHelper::NumFormat($totalMts);
					$result->$nroManzana =  SimpleTypesHelper::NumFormat($valor);
				}
				return $result;
			}else{
				return false ;
			}
		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $arrManzanas, $e);		 
			throw new ErrorException("No se pudo calcular el porcentaje de recargo para cada manzana.");
		}
	}

	public static function GetByNumero($nroManzana){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
				" where nroManzana= :nroManzana");
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
			
			if($tipoLiquidacion == LiquidacionTypeEnum::FondoReserva)
				$consulta = $objetoAccesoDato->RetornarConsulta("select montoFondoReserva as monto from " . static::class . 
					" where id = :idManzana");
			else
				$consulta = $objetoAccesoDato->RetornarConsulta("select montoFondoPrevision as monto from " . static::class .
					" where id = :idManzana");

			$consulta->bindValue(':idManzana' , $idManzana, \PDO::PARAM_INT);	
			$consulta->execute();
			$objEntidad= PDOHelper::FetchObject($consulta, static::class);

			return $objEntidad->monto;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $nroManzana, $e);		 
			throw new ErrorException("No se pudo recuperar el monto del fondo especial para la manzana de id " . $idManzana);
		}
	}

}//class