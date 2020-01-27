<?php

class UFApi{
    
    public static function Insert($request, $response, $args){
        $apiParams = $request->getParsedBody();
        $objUF = new UF($apiParams);
   
        if(!Funciones::Exists($objUF, "nroUF"))
            if(Funciones::InsertOne($objUF, true) > 0)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        else 
            return $response->withJson("El nro de unidad funcional ingresado no se encuentra disponible.", 400);
    }

    public static function GetByManzanaAndNumero($request, $response) {

        $apiParams = $request->getQueryParams();

        $result = UF::GetByManzanaAndNumero($apiParams['idManzana'], $apiParams['nroUF']);

        if($result != false)
            return $response->withJson($res, 200);
        else
            return $response->withJson("No se encontraron resultados", 400);
    }
    
}//class
