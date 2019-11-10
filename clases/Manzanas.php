<?php

require_once "AccesoDatos.php";
require_once "_FuncionesEntidades.php";
require_once "NumHelper.php";

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

	//	Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':mtsCuadrados'   	,$objEntidad->mtsCuadrados    ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   	,$objEntidad->tipoVivienda	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio'	,$objEntidad->nombreConsorcio ,\PDO::PARAM_STR);
	}

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
						$result->$idManzana = NumHelper::Format($manzana['mtsCuadrados']);
						$totalMts += NumHelper::Format($manzana['mtsCuadrados']);
						break;
					}
				}	
			}
			// Itero para calcular el coeficiente de cada manzana y actualizar el result final.
			foreach ($arrManzanas as $idManzana) {
				$valor =  (NumHelper::Format($result->$idManzana) * 100) / NumHelper::Format($totalMts);
				$result->$idManzana =  NumHelper::Format($valor, 3);
			}
			return $result;
		}else{
			return false ;
		}
	}

}//class