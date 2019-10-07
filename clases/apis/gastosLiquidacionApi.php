<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../GastosLiquidaciones.php';
include_once __DIR__ . '/../RelacionesGastos.php';

class GastoLiquidacionApi{

    public static function Insert($request, $response, $args){
        //Proceso los datos recibidos por body
        $apiParams = $request->getParsedBody();
        $arrGastos = $apiParams["GastosLiquidaciones"];

        try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
                  
            for ($i = 0; $i < sizeof($arrGastos); $i++) {
                $gasto = new GastosLiquidaciones($arrGastos[$i]);
                if(Funciones::InsertOne($gasto))
                    for ($j = 0; $j < sizeof($arrGastos[$i]["RelacionesGastos"]); $j++) {
                        $relacion = new RelacionesGastos($arrGastos[$i]["RelacionesGastos"][$j]);
                        if(!Funciones::InsertOne($relacion))                       
                            throw new Exception("No se pudieron guardar las relaciones de los gastos correctamente.");
                    }
                else
                    throw new Exception("No se pudo guardar los gastos correctamente.");
            }
            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);
		} catch (Exception $e){
			$objetoAccesoDato->rollBack();
            return $response->withJson($e->getMessage(), 500);
		}
    }
	 
}//class