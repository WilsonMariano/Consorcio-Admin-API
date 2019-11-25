<?php

include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class UFApi{
    
    public static function Insert($request, $response, $args){
        $apiParams = $request->getParsedBody();

        $objUF = new UF($apiParams);
   
        //Valido que el id no esté duplicado antes de insertar
        if(!UF::IsDuplicated($objUF->nroUF))
            if(Funciones::InsertOne($objUF, true))
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        else 
            return $response->withJson("El nro de unidad funcional ingresado no se encuentra disponible.", 400);
    }
    
	public static function ValidateBuilding ($request, $response, $args){
        $apiParams = $request->getQueryParams();

        $objEntidad = UF::GetByEdificio($apiParams["edificio"]);
        
        if($objEntidad)
            return $response->withJson(true, 200); 
        else
            return $response->withJson("El nro de edificio ingresado no existe.", 200);  
    }

}//class
