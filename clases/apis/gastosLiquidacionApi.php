<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../GastosLiquidaciones.php';
include_once __DIR__ . '/../RelacionesGastos.php';

class GastoLiquidacionApi{

    public static function Insert($request, $response, $args){
        //Proceso los datos recibidos por body
        $arrGastos = $request->getParsedBody();

        try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
                  
            for ($i = 0; $i < sizeof($arrGastos); $i++) {
                $gasto = new GastosLiquidaciones($arrGastos[$i]);
                if(Funciones::InsertOne($gasto)){
                    $gasto->id = $objetoAccesoDato->RetornarUltimoIdInsertado();
                    for ($j = 0; $j < sizeof($arrGastos[$i]["RelacionesGastos"]); $j++) {
                        $relacion = new RelacionesGastos($arrGastos[$i]["RelacionesGastos"][$j]);
                        $relacion->idGastosLiquidaciones = $gasto->id;
                        if(!Funciones::InsertOne($relacion))                       
                            throw new Exception("No se pudieron guardar las relaciones de los gastos correctamente.");
                    }
                }else{
                    throw new Exception("No se pudo guardar los gastos correctamente.");
                }
            }
            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);
		} catch (Exception $e){
			$objetoAccesoDato->rollBack();
            return $response->withJson($e->getMessage(), 500);
		}
    }

    public static function Delete($request, $response, $args){
        //Proceso los datos recibidos por body
        $arrIdGastos = $request->getParsedBody();

        try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
                  
            for($i = 0; $i < sizeof($arrIdGastos); $i++){
                if(Funciones::DeleteOne($arrIdGastos[$i],"GastosLiquidaciones")){
                    //Elimino todas las relaciones del gasto (si existen)
                    if(RelacionesGastos::Exists($arrIdGastos[$i]))    
                        if(!RelacionesGastos::DeleteAll($arrIdGastos[$i]))                          
                            throw new Exception("No se pudieron eliminar las relaciones de los gastos correctamente.");
                }else{
                    throw new Exception("No se pudieron eliminar los gastos correctamente.");
                }
            }
            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);
		} catch (Exception $e){
			$objetoAccesoDato->rollBack();
            return $response->withJson($e->getMessage(), 500);
		}
    }

}//class