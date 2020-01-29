<?php

class CtasCtesApi{
    
    public static function ProcessPayment($request, $response, $args){
        $apiParams = $request->getParsedBody();
    }

    public static function GetDeudas ($request, $response, $args){
        $apiParams = $request->getQueryParams();

        $listado= CtasCtes::GetDeudas($apiParams['idUF']);
		
		if($listado)
			return $response->withJson($listado, 200); 		
        else
            return $response->withJson(false, 400);
    }

}//class
