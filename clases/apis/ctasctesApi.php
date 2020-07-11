<?php

class CtasCtesApi{
    
    public static function ProcessPayment($request, $response, $args){
        try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $objetoAccesoDato->beginTransaction();
            
            $apiParams = $request->getParsedBody();

            for($i = 0; $i < sizeof($apiParams["arrDeudas"]); $i++){
                $deuda = ($apiParams["arrDeudas"][$i]);
                Liquidaciones::UpdateSaldos($deuda["idLiquidacion"], $deuda["montoPagar"]);
            }

            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);

		}catch(Exception $e){
            $objetoAccesoDato->rollBack();
            ErrorHelper::LogError(__FUNCTION__, $apiParams, $e);
            return $response->withJson($e->getMessage(), 500);
		}
    }

    public static function GetDeudas($request, $response, $args){
        $apiParams = $request->getQueryParams();
        $listado= CtasCtes::GetDeudas($apiParams['idUF']);
		
		if($listado)
			return $response->withJson($listado, 200); 		
        else
            return $response->withJson(false, 400);
    }

    public static function GetMovimientos($request, $response, $args){
        $apiParams = $request->getQueryParams();
        $listado= CtasCtes::GetMovimientos($apiParams['idUF']);

        if($listado)
            return $response->withJson($listado, 200); 		
        else
            return $response->withJson(false, 400);
    }

}//class
