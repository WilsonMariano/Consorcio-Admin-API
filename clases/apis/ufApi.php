<?php

include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class UFApi{
    
    public static function Insert($request, $response, $args){
        $apiParams = $request->getParsedBody();
        $objUF = new UF($apiParams);
   
        if(!UF::IsDuplicated($objUF))
            if(Funciones::InsertOne($objUF, true) > 0)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        else 
            return $response->withJson("El nro de unidad funcional ingresado no se encuentra disponible.", 400);
    }
    
}//class
