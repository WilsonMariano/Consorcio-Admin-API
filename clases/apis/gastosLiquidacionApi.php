<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../GastosLiquidaciones.php';
include_once __DIR__ . '/../RelacionesGastos.php';

class GastoLiquidacionApi{

    /**
     * Valida que la liquidacion global exista en la bd.
     */
    private static function IsValid($gasto){
        if(!Funciones::GetOne($gasto->idLiquidacionGlobal, "LiquidacionesGlobales")) 
            throw new Exception("La liquidación global ingresada no existe.");

        return true;
    }

    public static function Insert($request, $response, $args){
        try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $objetoAccesoDato->beginTransaction();
            
            $arrGastos = $request->getParsedBody();
                  
            for($i = 0; $i < sizeof($arrGastos); $i++){
                $gasto = new GastosLiquidaciones($arrGastos[$i]);
                if(self::IsValid($gasto))
                    if(Funciones::InsertOne($gasto)){
                        $gasto->id = $objetoAccesoDato->RetornarUltimoIdInsertado();
                        for($j = 0; $j < sizeof($arrGastos[$i]["RelacionesGastos"]); $j++){
                            $relacion = new RelacionesGastos($arrGastos[$i]["RelacionesGastos"][$j]);
                            $relacion->idGastosLiquidaciones = $gasto->id;
                            if(!RelacionesGastos::IsValid($relacion) || !Funciones::InsertOne($relacion))                       
                                throw new Exception("No se pudieron guardar las relaciones de los gastos correctamente.");
                        }
                    }else{
                        throw new Exception("No se pudo guardar los gastos correctamente.");
                    }
            }
            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);
		}catch(Exception $e){
			$objetoAccesoDato->rollBack();
            return $response->withJson($e->getMessage(), 500);
		}
    }
   
    public static function Delete($request, $response, $args){
        //Proceso los datos recibidos por body
        $arrIdGastos = $request->getParsedBody();

        try{  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
                  
            for($i = 0; $i < sizeof($arrIdGastos); $i++){
                if(Funciones::DeleteOne($arrIdGastos[$i],"GastosLiquidaciones")){
                    //Elimino todas las relaciones del gasto
                        if(!RelacionesGastos::DeleteAll($arrIdGastos[$i]))                          
                            throw new Exception("No se pudieron eliminar las relaciones de los gastos correctamente.");
                } else {
                    throw new Exception("No se pudieron eliminar los gastos correctamente.");
                }
            }
            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);
		}catch(Exception $e){
			$objetoAccesoDato->rollBack();
            return $response->withJson($e->getMessage(), 500);
		}
    }
    
}//class