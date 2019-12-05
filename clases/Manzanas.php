<?php

require_once "AccesoDatos.php";
require_once "_FuncionesEntidades.php";
require_once "Helper.php";

class Manzanas{

	//	Atributos
	public $id;
	public $nroManzana;
	public $mtsCuadrados;
	public $tipoVivienda;
	public $nombreConsorcio;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->id = $arrData['nroManzana'];
			$this->mtsCuadrados = $arrData['mtsCuadrados'];
			$this->tipoVivienda = $arrData['tipoVivienda'] ?? null;
			$this->nombreConsorcio = $arrData['nombreConsorcio'];
		}
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':nroManzana'   	,$objEntidad->nroManzana    ,\PDO::PARAM_INT);
		$consulta->bindValue(':mtsCuadrados'   	,$objEntidad->mtsCuadrados    ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   	,$objEntidad->tipoVivienda	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio'	,$objEntidad->nombreConsorcio ,\PDO::PARAM_STR);
	}

	/**
	 * Calcula el porcentaje de gasto que le corresponde a cada una de las manzanas recibidas. Devuelve un array con la estructura [nroManzana] = [coeficiente].
	 * Recibe por parámetro un array con los nroManzana para los cuales calculará el coeficiente.
	 */
	public static function GetPorcentajes($arrManzanas){
		//Traigo todas las manzanas
		$manzanas = Funciones::GetAll("Manzanas");

		if($manzanas){
			$totalMts = 0;
			$result = new \stdClass();
			// Burbujeo para armar el result(preliminar) y tambien calcular el total de mts cuadrados entre todas las manzanas recibidas por param
			foreach ($arrManzanas as $nroManzana) {
				foreach ($manzanas as $manzana) {
					if($nroManzana == $manzana['nroManzana']) {
						$result->$nroManzana = Helper::NumFormat($manzana['mtsCuadrados']);
						$totalMts += Helper::NumFormat($manzana['mtsCuadrados']);
						break;
					}
				}	
			}

			// Itero para calcular el coeficiente de cada manzana y actualizar el result final.
			foreach ($arrManzanas as $nroManzana) {
				$valor =  (Helper::NumFormat($result->$nroManzana) * 100) / Helper::NumFormat($totalMts);
				$result->$nroManzana =  Helper::NumFormat($valor, 0);
			}
			return $result;
		}else{
			return false ;
		}
	}

}//class