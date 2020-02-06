<?php   

class GastoLiquidacionApi{

    /**
     * Valida que la liquidacion global exista en la bd.
     */
    private static function IsValid($gasto){
        if(!Funciones::GetOne($gasto->idLiquidacionGlobal, LiquidacionesGlobales::class)) 
            throw new Exception("La liquidación global ingresada no existe.");
        return true;
    }

    private static function ImputarContraFondoEsp($gastoLiquidacion){
        
        $movFondos = new MovimientosFondosEsp();
        // $movFondos->idManzana = 

        // $fondo = new MovimientosFR();
        // $fondo->idMovimientoFondoEsp = ;
        // $fondo->idGastoLiquidacion = ;

        return true;
    }

    public static function Insert($request, $response, $args){
        try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $objetoAccesoDato->beginTransaction();
            
            $arrGastos = $request->getParsedBody();
                  
            for($i = 0; $i < sizeof($arrGastos); $i++){
                $gasto = new GastosLiquidaciones($arrGastos[$i]);
                if(self::IsValid($gasto)){

                    if($arrGastos[$i]["imputaFondoEspecial"])
                        self::ImputarContraFondoEsp($gasto);

                    if(Funciones::InsertOne($gasto) > 0){
                        $gasto->id = $objetoAccesoDato->RetornarUltimoIdInsertado();
                        for($j = 0; $j < sizeof($arrGastos[$i][RelacionesGastos::class]); $j++){
                            $relacion = new RelacionesGastos($arrGastos[$i][RelacionesGastos::class][$j]);
                            $relacion->idGastosLiquidaciones = $gasto->id;
                            if(!RelacionesGastos::IsValid($relacion) || !Funciones::InsertOne($relacion))                       
                                throw new Exception("No se pudieron guardar las relaciones de los gastos correctamente.");
                        }
                    }else{
                        throw new Exception("No se pudo guardar los gastos correctamente.");
                    }
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
                if(Funciones::DeleteOne($arrIdGastos[$i], GastosLiquidaciones::class)){
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