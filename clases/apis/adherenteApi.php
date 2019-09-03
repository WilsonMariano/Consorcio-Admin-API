<?php

    include_once __DIR__ . '/../Adherentes.php';

    class AdherenteApi
    {
		
        public static function Insert($request, $response, $args){

            $datosRecibidos = $request->getParsedBody();

            $adherente = new Adherentes();
            $adherente->id           = $datosRecibidos['id'];
            $adherente->nombre       = $datosRecibidos['nombre'];
            $adherente->apellido     = $datosRecibidos['apellido'];
            $adherente->nroDocumento = $datosRecibidos['nroDocumento'];
            $adherente->telefono     = $datosRecibidos['telefono'];
            $adherente->email        = $datosRecibidos['email'];
 
            $resultado = adherentes::Insert($adherente);
    
            if(is_numeric($resultado) == true)
                return $response->withJson(true, 200);
            else
                return $response->withJson("Ha ocurrido un error insertando el adherente. Inténtelo nuevamente.", 500);
        }

		


    }
