<?php

require_once "AccesoDatos.php";
require_once "_FuncionesEntidades.php";

class Manzanas
{

	//	Atributos
	public $id;
	public $mtsCuadrados;
	public $tipoVivienda;
	public $nombreConsorcio;


	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':mtsCuadrados'   	,$objEntidad->mtsCuadrados    ,\PDO::PARAM_STR);
		$consulta->bindValue(':tipoVivienda'   	,$objEntidad->tipoVivienda	  ,\PDO::PARAM_STR);
		$consulta->bindValue(':nombreConsorcio'	,$objEntidad->nombreConsorcio ,\PDO::PARAM_STR);
		
		return $consulta;
	}

	public static function GetCoeficientes($arrManzanas){
		//Traigo todas las manzanas
		$manzanas = Funciones::GetAll("Manzanas");
		
		if($manzanas){
			$totalMts = 0;
			$result = new \stdClass();
			// Burbujeo para armar el result(preliminar) y tambien calcular el total de mts cuadrados entre todas las manzanas recibidas por param
			foreach ($arrManzanas as $id) {
				foreach ($manzanas as $manzana) {
					if($id == $manzana['id']) {
						$result->$id = intval($manzana['mtsCuadrados']);
						$totalMts += intval($manzana['mtsCuadrados']);
						break;
					}
				}	
			}
			// Itero para calcular el coeficiente de cada manzana y actualizar el result final.
			foreach ($arrManzanas as $id) {
				$valor =  (intval($result->$id) * 100) / intval($totalMts);
				$result->$id =  number_format($valor, 2, ',', ' ');
			}
	
			return $result;
	
		}else{
			return false ;
		}
	}

}//class