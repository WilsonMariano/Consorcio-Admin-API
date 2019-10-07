<?php   

include_once __DIR__ . '/../LiquidacionesGlobales.php';
include_once __DIR__ . '/../Diccionario.php';

class LiquidacionGlobalApi{

    private static function IsValid($liquidacionGbl){
        // Valido que el periodo ingresado no haya sido ingresado previamente. (Para evitar periodos duplicados)
        return !LiquidacionesGlobales::CheckByPeriod($liquidacionGbl->mes, $liquidacionGbl->anio);
    }

    public static function Insert($request, $response, $args){
        //Proceso los datos recibidos por body
        $apiParams = $request->getParsedBody();

        //Obtengo instancia de LiquidacionGlobal
        $liquidacionGbl = new LiquidacionesGlobales($apiParams);
        $liquidacionGbl->tasaInteres = Diccionario::GetValue("TASA_INTERES");

        if(self::IsValid($liquidacionGbl))
            if(Funciones::InsertOne($liquidacionGbl))
                return $response->withJson(true, 200); 		
            else
                return $response->withJson(false, 500);
        else
            return $response->withJson("El período ingresado ya se encuentra registrado.", 400);				
    }
    
    public static function GetOneFromView($request, $response, $args){
        $apiParams = $request->getQueryParams();
		$id = json_decode($args['id']);

        $objEntidad = LiquidacionesGlobales::GetOneFromView($id);
        
        if($objEntidad)
            return $response->withJson($objEntidad, 200); 
        else
            return $response->withJson(false, 400);  
    }
	 
}//class