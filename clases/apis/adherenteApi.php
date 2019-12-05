<?php

include_once __DIR__ . '/../Adherentes.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class AdherenteApi{
    
    public static function Insert($request, $response, $args){
        $apiParams = $request->getParsedBody();

        $objAdherente = new Adherentes($apiParams);

        //Valido que el id no esté duplicado antes de insertar
        if(!Funciones::IsDuplicated($objAdherente, "nroAdherente"))
            if(Funciones::InsertOne($objAdherente, true))
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 500);
        else 
            return $response->withJson("El nro de adherente ingresado no se encuentra disponible.", 400);
    }

}//class
