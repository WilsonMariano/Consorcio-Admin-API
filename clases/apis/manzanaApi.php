<?php

class ManzanaApi {

	/**
	 * Calcula el porcentaje de gasto que le corresponde a cada una de las manzanas recibidas. Devuelve un array con la estructura [nroManzana] = [coeficiente].
	 * Recibe por parámetro un array con los nroManzana para los cuales calculará el coeficiente.
	 */
	public static function GetPorcentajes($arrManzanas){
		try{
			//Traigo todas las manzanas
			$manzanas = Funciones::GetAll(Manzanas::class);

			if($manzanas){
				$totalMts = 0;
				$result = new \stdClass();
				// Burbujeo para armar el result(preliminar) y tambien calcular el total de mts cuadrados entre todas las manzanas recibidas por param
				foreach ($arrManzanas as $nroManzana) {
					foreach ($manzanas as $manzana) {
						if($nroManzana == $manzana->nroManzana) {
							$result->$nroManzana = NumHelper::NumFormat($manzana->mtsCuadrados);
							$totalMts += NumHelper::NumFormat($manzana->mtsCuadrados);
							break;
						}
					}	
				}

				// Itero para calcular el coeficiente de cada manzana y actualizar el result final.
				foreach ($arrManzanas as $nroManzana) {
					$valor =  (NumHelper::NumFormat($result->$nroManzana) * 100) / NumHelper::NumFormat($totalMts);
					$result->$nroManzana =  NumHelper::NumFormat($valor);
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

}