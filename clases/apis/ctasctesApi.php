<?php

include_once __DIR__ . '/../CtasCtes.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class CtasCtesApi{
    
    public static function ProcessPayment($request, $response, $args){
        $apiParams = $request->getParsedBody();
    }

    public static function GetDeudas ($request, $response, $args){
        $apiParams = $request->getQueryParams();

        if(CtasCtes::GetDeudas($apiParams['idManzana'], $apiParams['nroUF']))
            return $response->withJson(true, 200); 		
        else
            return $response->withJson(false, 500);
    }

}//class
