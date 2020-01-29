<?php

include_once __DIR__ . '/../NotasDebito.php';
include_once __DIR__ . '/../CtasCtes.php';
include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class NotaCreditoDebitoApi{
    
    public static function New($request, $response, $args){
        $apiParams = $request->getParsedBody();
        
        if($apiParams['tipoDocumento'] == 'NC'){
            // todo: elegir segun el tipo de entidad
        }else{

        }
   
        if(!UF::Exists($objUF))
            if(Funciones::InsertOne($objUF, true) > 0)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        else 
            return $response->withJson("El nro de unidad funcional ingresado no se encuentra disponible.", 400);
    }
    
}//class
