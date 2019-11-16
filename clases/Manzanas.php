<?php

require_once "AccesoDatos.php";
require_once "_FuncionesEntidades.php";
require_once "Helper.php";

class Manzanas{

	//	Atributos
	public $id;
	public $mtsCuadrados;
	public $tipoVivienda;
	public $nombreConsorcio;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
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
		
		$consulta->bindValue(':mtsCuadrados'   	,$objEntidad->mtsCuadrados    ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   	,$objEntidad->tipoVivienda	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio'	,$objEntidad->nombreConsorcio ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un array con la estructura [idManzana] = [coeficiente].
	 * Recibe por parámetro un array con los idManzana para los cuales calculará el coeficiente.
	 */
	public static function GetCoeficientes($arrManzanas){
		//Traigo todas las manzanas
		$manzanas = Funciones::GetAll("Manzanas");
		
		if($manzanas){
			$totalMts = 0;
			$result = new \stdClass();
			// Burbujeo para armar el result(preliminar) y tambien calcular el total de mts cuadrados entre todas las manzanas recibidas por param
			foreach ($arrManzanas as $idManzana) {
				foreach ($manzanas as $manzana) {
					if($idManzana == $manzana['id']) {
						$result->$idManzana = Helper::NumFormat($manzana['mtsCuadrados']);
						$totalMts += Helper::NumFormat($manzana['mtsCuadrados']);
						break;
					}
				}	
			}
			// Itero para calcular el coeficiente de cada manzana y actualizar el result final.
			foreach ($arrManzanas as $idManzana) {
				$valor =  (Helper::NumFormat($result->$idManzana) * 100) / Helper::NumFormat($totalMts);
				$result->$idManzana =  Helper::NumFormat($valor, 0);
			}
			return $result;
		}else{
			return false ;
		}
	}

}//class