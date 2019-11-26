<?php

include_once __DIR__ . '/../CtasCtes.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class CtasCtesApi{
    
    public static function ProcessPayment($request, $response, $args){
        $apiParams = $request->getParsedBody();
    
        
        
    }
    
	public static function CreditNote ($request, $response, $args){
        $apiParams = $request->getQueryParams();

        
    }

}//class
