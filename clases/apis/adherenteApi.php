<?php

class AdherenteApi{
    
    public static function Insert($request, $response, $args){
        $apiParams = $request->getParsedBody();

        $objAdherente = new Adherentes($apiParams);

        //Valido que el id no esté duplicado antes de insertar
        if(!Funciones::Exists($objAdherente, "nroAdherente"))
            if(Funciones::InsertOne($objAdherente, true) > 0)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 500);
        else 
            return $response->withJson("El nro de adherente ingresado no se encuentra disponible.", 400);
    }

}//class
